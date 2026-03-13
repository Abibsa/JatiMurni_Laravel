<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }} - Jati Murni</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; font-size: 14px; }
        .invoice-container { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 40px; border-bottom: 2px solid #b22222; padding-bottom: 20px; }
        .brand h1 { margin: 0; color: #b22222; font-size: 28px; }
        .brand p { margin: 5px 0 0; color: #777; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0; color: #333; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-box { width: 45%; }
        .info-box h4 { margin-top: 0; margin-bottom: 10px; color: #555; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f8f8; color: #333; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row th { background-color: #fff; font-size: 16px; border-top: 2px solid #b22222; }
        .total-row td { font-size: 16px; font-weight: bold; color: #b22222; border-top: 2px solid #b22222; }
        .footer { text-align: center; color: #777; margin-top: 50px; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        @media print {
            body { padding: 0; }
            .invoice-container { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
        .btn-print { background: #b22222; color: #fff; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; font-size: 14px; margin-bottom: 20px; display: inline-block; text-decoration: none; }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Invoice PDF</button>
        <a href="javascript:history.back()" style="color: #666; margin-left: 15px; text-decoration: none;">Kembali</a>
    </div>

    <div class="invoice-container">
        <div class="header">
            <div class="brand">
                <h1>MEUBEL JATI MURNI</h1>
                <p>Jl. Contoh Alamat No. 123, Kota Pusat<br>WhatsApp: 0812-3456-7890</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>No. Order:</strong> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}<br>
                <strong>Status:</strong> <span style="text-transform: uppercase; color: {{ $order->status == 'completed' ? '#28a745' : ($order->status == 'cancelled' ? '#dc3545' : '#ffc107') }}">{{ $order->status }}</span></p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h4>Ditagihkan Kepada:</h4>
                <p><strong>{{ $order->user->name ?? 'Pelanggan' }}</strong><br>
                Email: {{ $order->user->email ?? '-' }}<br>
                Telepon: {{ $order->user->phone ?? '-' }}</p>
            </div>
            <div class="info-box border-left">
                <h4>Alamat Pengiriman:</h4>
                <p>{{ $order->shipping_address ?? 'Sesuai dengan alamat profil' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Produk</th>
                    <th class="text-center">Kuantitas</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="3" class="text-right">TOTAL PEMBAYARAN:</th>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($order->payment)
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <strong>Informasi Pembayaran:</strong> Telah dibayar menggunakan {{ $order->payment->payment_method }} pada {{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d M Y') }} (Status: {{ strtoupper($order->payment->status) }}).
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih telah berbelanja di Meubel Jati Murni. Produk kualitas terbaik untuk kenyamanan rumah Anda.<br>
            Dokumen ini sah tanpa tanda tangan.</p>
        </div>
    </div>
</body>
</html>
