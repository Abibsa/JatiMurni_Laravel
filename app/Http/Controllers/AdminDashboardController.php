<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total Penjualan Bulan Ini (Pesanan Selesai atau Shipped/Processing)
        $monthlyRevenue = Order::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->whereIn('status', ['shipped', 'completed', 'processing'])
                               ->sum('total_amount');

        // Total Pesanan Belum Diproses atau Menunggu Konfirmasi
        $pendingOrders = Order::where(function ($q) {
            $q->where('status', 'pending')
              ->orWhereHas('payment', function ($query) {
                  $query->where('status', 'pending');
              });
        })->count();

        // Total Produk
        $totalProducts = Product::count();

        // Total Pelanggan
        $totalCustomers = User::where('role', 'customer')->count();

        // Grafik Penjualan 7 Hari Terakhir
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyTotal = Order::whereDate('created_at', $date)
                               ->whereIn('status', ['shipped', 'completed', 'processing'])
                               ->sum('total_amount');
            
            $last7Days->push([
                'date' => $date->format('d M'),
                'total' => $dailyTotal
            ]);
        }

        $labels = $last7Days->pluck('date');
        $totals = $last7Days->pluck('total');

        // 5 Pesanan Terbaru
        $recentOrders = Order::with('user', 'payment')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'monthlyRevenue', 
            'pendingOrders', 
            'totalProducts', 
            'totalCustomers',
            'labels',
            'totals',
            'recentOrders'
        ));
    }
}
