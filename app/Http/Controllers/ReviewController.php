<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Simpan review baru dari customer.
     * Customer hanya bisa review produk dari order yang berstatus 'completed'.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak memberikan review untuk pesanan ini.');
        }

        // Pastikan order sudah completed
        if ($order->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa memberikan review setelah pesanan berstatus "Selesai".');
        }

        // Cek apakah sudah pernah review produk ini di order ini
        $existing = Review::where('user_id', auth()->id())
                          ->where('product_id', $request->product_id)
                          ->where('order_id', $request->order_id)
                          ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah pernah memberikan review untuk produk ini pada pesanan ini.');
        }

        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Review Anda berhasil disimpan.');
    }
}
