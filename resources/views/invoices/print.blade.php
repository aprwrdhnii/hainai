<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Outfit', sans-serif;
            background: #fff;
            color: #1f2937;
            font-size: 14px;
            line-height: 1.5;
            padding: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #ea580c; /* Orange-600 */
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .company-info {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: 800;
            color: #ea580c;
            text-align: right;
            text-transform: uppercase;
        }

        .meta-grid {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
        }

        .meta-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .meta-box p {
            font-weight: 600;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            text-align: left;
            padding: 12px 10px;
            background: #f9fafb;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            color: #4b5563;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .total-box {
            width: 300px;
            margin-left: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .total-row.final {
            border-bottom: none;
            font-size: 20px;
            font-weight: 800;
            color: #ea580c;
            padding-top: 20px;
            border-top: 2px solid #1f2937;
            margin-top: 10px;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <div>
            <div class="logo">Bengkel HaiNai</div>
            <div class="company-info">
                Jl. Ahmad Yani Km. 7, Banjarmasin<br>
                Telp/WA: 0812-3456-7890
            </div>
        </div>
        <div>
            <div class="invoice-title">INVOICE</div>
            <div style="text-align: right; margin-top: 5px; font-weight: 600;">#{{ $invoice->invoice_number }}</div>
        </div>
    </div>

    <div class="meta-grid">
        <div class="meta-box">
            <h3>Ditagihkan Kepada</h3>
            <p>{{ $invoice->customer->name ?? 'Pelanggan Umum' }}</p>
            @if($invoice->customer && $invoice->customer->address)
            <div style="font-size: 13px; color: #4b5563;">{{ $invoice->customer->address }}</div>
            @endif
        </div>
        <div class="meta-box" style="text-align: right;">
            <h3>Tanggal Transaksi</h3>
            <p>{{ $invoice->created_at->format('d M Y') }}</p>
            <div style="font-size: 13px; color: #4b5563;">Jam: {{ $invoice->created_at->format('H:i') }} | Petugas: {{ auth()->user()->name ?? 'Admin' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Deskripsi Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->service_labor_cost > 0)
            <tr>
                <td>
                    <b>Jasa Servis & Perbaikan</b><br>
                    <small style="color: #6b7280;">Kendaraan: {{ $invoice->vehicle->name ?? '-' }} ({{ $invoice->vehicle->plate_number ?? '' }})</small>
                </td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($invoice->service_labor_cost, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($invoice->service_labor_cost, 0, ',', '.') }}</td>
            </tr>
            @endif

            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row final">
            <span>TOTAL</span>
            <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
        </div>
        <div style="text-align: right; margin-top: 10px; font-size: 12px; font-weight: 600; color: {{ $invoice->status == 'paid' ? '#16a34a' : '#ea580c' }}">
            STATUS: {{ $invoice->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
        </div>
    </div>

    <div class="footer">
        Terima kasih atas kepercayaan Anda merawat kendaraan di Bengkel HaiNai.<br>
        Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.
    </div>

</body>
</html>
