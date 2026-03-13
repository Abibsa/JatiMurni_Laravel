<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function detail($id)
    {
        $order = Order::with(['items.product', 'payment'])->findOrFail($id);
        
        // Ensure only the owner or an admin can view this
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('pengguna.transaksi.detail', compact('order'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'payment_method' => 'required|string|max:50',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('proof_image')->store('payments', 'public');

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'customer_name' => auth()->user()->name,
                'payment_method' => $request->payment_method,
                'payment_date' => now()->format('Y-m-d'),
                'amount_paid' => $order->total_amount,
                'proof_image' => $imagePath,
                'status' => 'pending'
            ]
        );

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.');
    }

    public function invoice($id)
    {
        $order = Order::with(['items.product', 'payment', 'user'])->findOrFail($id);

        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('pengguna.transaksi.invoice', compact('order'));
    }
}
