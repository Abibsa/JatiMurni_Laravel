<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup initial data
        Category::create([
            'name' => 'General',
            'slug' => 'general'
        ]);
        
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@test.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'phone' => '123',
            'address' => 'USA',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'cust_test@test.com',
            'password' => Hash::make('Cust123!'),
            'role' => 'customer',
            'phone' => '456',
            'address' => 'USA',
            'status' => 'active'
        ]);
    }

    public function test_customer_access_logic()
    {
        $customer = User::where('email', 'cust_test@test.com')->first();

        // Customer cannot access admin routes
        $this->actingAs($customer)->get('/dashboard-admin')->assertStatus(403);
        $this->actingAs($customer)->get('/pengguna')->assertStatus(403);
        $this->actingAs($customer)->get('/pembayaran')->assertStatus(403);
        $this->actingAs($customer)->get('/admin/produk')->assertStatus(403);

        // Customer CAN access user routes
        $this->actingAs($customer)->get('/dashboard-user')->assertStatus(200);
        $this->actingAs($customer)->get('/produk')->assertStatus(200);
    }

    public function test_admin_access_logic()
    {
        $admin = User::where('email', 'admin_test@test.com')->first();

        // Admin can access everything
        $this->actingAs($admin)->get('/dashboard-admin')->assertStatus(200);
        $this->actingAs($admin)->get('/pengguna')->assertStatus(200);
        $this->actingAs($admin)->get('/pembayaran')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/produk')->assertStatus(200);
    }

    public function test_transaksi_controller_detail()
    {
        $customer = User::where('email', 'cust_test@test.com')->first();
        $admin = User::where('email', 'admin_test@test.com')->first();
        
        $product = Product::create([
            'name' => 'Test Item',
            'category_id' => 1,
            'description' => '-',
            'price' => 100,
            'stock' => 10,
            'material' => '-',
            'dimensions' => '-',
            'slug' => 'test-item-123'
        ]);
        
        $order = Order::create([
            'user_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 100
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100,
            'subtotal' => 100
        ]);

        // Owner can access
        $this->actingAs($customer)->get('/transaksi/' . $order->id)->assertStatus(200);

        // Admin can access
        $this->actingAs($admin)->get('/transaksi/' . $order->id)->assertStatus(200);

        // Other customer cannot access
        $otherCustomer = User::create([
            'name' => 'Other',
            'email' => 'other@test.com',
            'password' => '123',
            'phone' => '000',
            'address' => 'X',
            'role' => 'customer'
        ]);
        $this->actingAs($otherCustomer)->get('/transaksi/' . $order->id)->assertStatus(403);
    }

    public function test_payment_creation_with_order_id()
    {
        $admin = User::where('email', 'admin_test@test.com')->first();
        
        $order = Order::create([
            'user_id' => $admin->id,
            'status' => 'pending',
            'total_amount' => 500
        ]);
        
        $data = [
            'order_id' => $order->id,
            'customer_name' => 'Verifier',
            'payment_method' => 'Transfer',
            'payment_date' => now()->format('Y-m-d'),
            'amount_paid' => 500,
            'status' => 'pending'
        ];

        $this->actingAs($admin)->post('/pembayaran', $data)->assertRedirect('/pembayaran');

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'customer_name' => 'Verifier'
        ]);
    }

    /**
     * Test Fitur 1: Keranjang Belanja & Checkout
     */
    public function test_cart_and_checkout_flow()
    {
        $customer = User::where('email', 'cust_test@test.com')->first();
        $product = Product::create([
            'name' => 'Produk Test 2',
            'category_id' => 1,
            'price' => 150000,
            'stock' => 5,
            'material' => 'Kayu',
            'dimensions' => '10x10',
            'description' => 'Deskripsi produk test',
            'slug' => 'produk-test-2-' . uniqid()
        ]);

        // 1. Tambah ke keranjang
        $this->actingAs($customer)
             ->post('/cart', ['product_id' => $product->id, 'quantity' => 2])
             ->assertRedirect('/cart');

        $this->assertDatabaseHas('carts', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // 2. Checkout
        $this->actingAs($customer)
             ->post('/cart/checkout')
             ->assertRedirect(); // Biasanya ke halaman transaksi terakhir

        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'status' => 'pending']);
    }

    /**
     * Test Fitur 2 & 5: Alur Pembayaran (Customer Upload & Admin Confirm)
     */
    public function test_payment_and_admin_confirmation()
    {
        $customer = User::where('email', 'cust_test@test.com')->first();
        $admin = User::where('email', 'admin_test@test.com')->first();
        
        $order = Order::create([
            'user_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 200000
        ]);

        // 1. Customer Upload Bukti Bayar
        $this->actingAs($customer)
             ->post('/transaksi/' . $order->id . '/pembayaran', [
                 'payment_method' => 'Bank Transfer',
                 'proof_image' => \Illuminate\Http\UploadedFile::fake()->image('bukti.jpg')
             ])->assertSessionHas('success');

        $this->assertEquals('processing', $order->fresh()->status);

        // 2. Admin Konfirmasi Pembayaran
        $payment = Payment::where('order_id', $order->id)->first();
        
        $this->actingAs($admin)
             ->put(route('admin.orders.confirm-payment', $payment->id))
             ->assertRedirect(route('admin.orders.index'));
             
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'confirmed'
        ]);
    }

    /**
     * Test Fitur 8: Sistem Review (Produk Bintang 5)
     */
    public function test_customer_review_submission()
    {
        $customer = User::where('email', 'cust_test@test.com')->first();
        $product = Product::create([
            'name' => 'Meja Jati',
            'category_id' => 1,
            'price' => 500000,
            'stock' => 2,
            'material' => 'Kayu',
            'dimensions' => '1x1',
            'description' => 'Deskripsi meja jati',
            'slug' => 'meja-jati-' . uniqid()
        ]);

        // Buat order yang sudah berstatus 'completed'
        $order = Order::create([
            'user_id' => $customer->id,
            'status' => 'completed',
            'total_amount' => 500000
        ]);

        // Kirim review
        $this->actingAs($customer)
             ->post('/review', [
                 'product_id' => $product->id,
                 'order_id' => $order->id,
                 'rating' => 5,
                 'comment' => 'Barangnya sangat bagus dan kokoh!'
             ])->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5
        ]);
    }
}
