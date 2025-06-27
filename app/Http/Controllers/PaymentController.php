<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::all();
        return view('admin.payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'payment_method' => 'required|string|max:50',
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,confirmed,rejected'
        ]);

        // Generate payment_code otomatis
        $lastPaymentWithCode = Payment::whereNotNull('payment_code')->orderBy('id', 'desc')->first();
        $nextNumber = $lastPaymentWithCode ? ((int)substr($lastPaymentWithCode->payment_code, 1)) + 1 : 1;
        $validated['payment_code'] = 'P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Optional: update payment_code untuk data lama yang masih null
        Payment::whereNull('payment_code')->get()->each(function($p, $i) {
            $p->payment_code = 'P' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $p->save();
        });

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment-proofs', 'public');
            $validated['proof_image'] = $path;
        }

        Payment::create($validated);

        Log::info('Payment created:', $validated);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $orders = Order::whereDoesntHave('payment')->orWhere('id', $payment->order_id)->get();
        return view('admin.payments.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:100',
                'payment_method' => 'required|string|max:50',
                'payment_date' => 'required|date',
                'amount_paid' => 'required|numeric|min:0',
                'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:pending,confirmed,rejected'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('edit_payment_id', $payment->id)
                ->withErrors($e->validator);
        }

        if ($request->hasFile('proof_image')) {
            // Delete old image if exists
            if ($payment->proof_image) {
                Storage::disk('public')->delete($payment->proof_image);
            }
            $path = $request->file('proof_image')->store('payment-proofs', 'public');
            $validated['proof_image'] = $path;
        }

        $payment->update($validated);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }
        
        $payment->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
} 