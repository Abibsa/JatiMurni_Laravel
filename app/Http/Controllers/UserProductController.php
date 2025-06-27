<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        return view('pengguna.produk.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pengguna.produk.show', compact('product'));
    }

    public function tambah(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        // Simpan pesanan baru
        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'qty' => 1,
            'status' => 'pending'
        ]);

        // Redirect ke detail transaksi
        return redirect()->route('transaksi.detail', $order->id);
    }

    public function tambahPesanan(Request $request, $id)
    {
        // logika tambah pesanan
    }
}
