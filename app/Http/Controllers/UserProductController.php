<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        $query = Product::with(['category', 'reviews.user', 'images']);
        
        // Cek jika tidak login, bisa pilih untuk show all atau limit.
        // Saat ini defaultnya nampilkan semua produk yang ada walau stok 0? 
        // Oh, tetap filter stok > 0.
        $query->where('stock', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->get();
        return view('pengguna.produk.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pengguna.produk.show', compact('product'));
    }

    public function tambah(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Dapatkan atau buat order pending untuk user ini
        // (Atau buat order baru setiap kali 'tambah' dipanggil - tergantung flow toko)
        // Di sini kita buat order baru setiap kali klik 'beli' sesuai flow sebelumnya
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => 0, // Akan diupdate otomatis oleh OrderItem boot
            'shipping_address' => auth()->user()->address ?? '-'
        ]);

        // 2. Tambahkan produk ke OrderItem
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'subtotal' => $product->price
        ]);

        // Redirect ke detail transaksi
        return redirect()->route('transaksi.detail', $order->id)->with('success', 'Produk berhasil dipesan!');
    }

    public function tambahPesanan(Request $request, $id)
    {
        // logika tambah pesanan
    }
}
