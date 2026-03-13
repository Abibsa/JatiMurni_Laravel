<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'payment'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        // Jika status order diset completed dan ada payment pendings, kita bisa bantu auto confirmed payment?
        // Secara logika real, payment dikonfirmasi dulu, baru order diproses.
        
        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function confirmPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'confirmed']);

        // Update corresponding order status
        if ($payment->order) {
            $payment->order->update(['status' => 'processing']);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function rejectPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'rejected']);

        // Update corresponding order status to pending again or cancelled
        if ($payment->order) {
            $payment->order->update(['status' => 'pending']);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Pembayaran ditolak.');
    }
}
