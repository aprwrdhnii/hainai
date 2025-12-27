@extends('layouts.app')
@section('title', 'Detail Invoice')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Invoice</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">#{{ $invoice->invoice_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Kembali
            </a>
            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/20 transition flex items-center gap-2">
                <i class="bi bi-printer"></i> Cetak
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="mb-6 {{ $invoice->status == 'paid' ? 'bg-green-500' : 'bg-orange-500' }} rounded-2xl p-6 text-white shadow-lg overflow-hidden relative">
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-white/80 font-medium text-sm mb-1 uppercase tracking-wider">Status Pembayaran</p>
                <h2 class="text-2xl font-bold">{{ $invoice->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS (BON)' }}</h2>
            </div>
            <div class="text-right">
                <p class="text-white/80 font-medium text-sm mb-1">Total Tagihan</p>
                <h2 class="text-3xl font-black">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 text-white/10">
            <i class="bi {{ $invoice->status == 'paid' ? 'bi-check-circle-fill' : 'bi-receipt' }} text-9xl"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Invoice Header -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-700">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Ditagihkan Kepada</h5>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $invoice->customer->name ?? 'Pelanggan Umum' }}</h3>
                    @if($invoice->customer)
                        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $invoice->customer->phone }}</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">{{ $invoice->customer->address }}</p>
                    @endif
                    
                    @if($invoice->vehicle)
                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-900/30 rounded-xl inline-block">
                        <p class="text-xs text-gray-500 font-medium">Kendaraan</p>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $invoice->vehicle->name }} <span class="text-gray-400 font-normal">({{ $invoice->vehicle->plate_number ?? '-' }})</span></p>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Info Transaksi</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between md:justify-end gap-8">
                            <span class="text-gray-500">No. Invoice</span>
                            <span class="font-bold text-gray-800 dark:text-white font-mono">#{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between md:justify-end gap-8">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $invoice->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between md:justify-end gap-8">
                            <span class="text-gray-500">Jam</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $invoice->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="p-8">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 font-bold tracking-wider border-b-2 border-gray-100 dark:border-gray-700">
                        <th class="pb-4">Deskripsi</th>
                        <th class="pb-4 text-center">Banyaknya</th>
                        <th class="pb-4 text-right">Harga Satuan</th>
                        <th class="pb-4 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    <!-- Jasa Servis -->
                    @if($invoice->service_labor_cost > 0)
                    <tr class="border-b border-gray-50 dark:border-gray-800">
                        <td class="py-4">
                            <p class="font-bold text-gray-800 dark:text-white">Jasa Servis</p>
                            <p class="text-xs text-gray-500">Biaya perbaikan / pemasangan</p>
                        </td>
                        <td class="py-4 text-center">1</td>
                        <td class="py-4 text-right">Rp {{ number_format($invoice->service_labor_cost, 0, ',', '.') }}</td>
                        <td class="py-4 text-right font-bold">Rp {{ number_format($invoice->service_labor_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif

                    <!-- Spareparts -->
                    @foreach($invoice->items as $item)
                    <tr class="border-b border-gray-50 dark:border-gray-800">
                        <td class="py-4">
                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->item_name }}</p>
                        </td>
                        <td class="py-4 text-center">{{ $item->qty }}</td>
                        <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="pt-6 text-right text-gray-500 font-medium">Grand Total</td>
                        <td class="pt-6 text-right text-2xl font-black text-gray-800 dark:text-white">
                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Footer Info -->
        <div class="p-8 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 text-center text-sm text-gray-500">
            <p>Terima kasih atas kepercayaan Anda.</p>
        </div>
    </div>
</div>
@endsection
