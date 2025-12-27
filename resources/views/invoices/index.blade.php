@extends('layouts.app')
@section('title', 'Daftar Invoice')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Invoice & Tagihan</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola pembayaran dan riwayat transaksi</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('invoices.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Buat Invoice Manual
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Pendapatan Bulan Ini</p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($thisMonthRevenue ?? 0, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Menunggu Pembayaran (Bon)</p>
        <h3 class="text-2xl font-bold text-orange-600">Rp {{ number_format($pendingPayment ?? 0, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $invoices->total() }}</h3>
    </div>
</div>

<!-- Invoice List -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 font-bold tracking-wider">
                    <th class="px-6 py-4 rounded-tl-2xl">No. Invoice</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right rounded-tr-2xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-gray-700 dark:text-gray-300">#{{ $invoice->invoice_number }}</span>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $invoice->created_at->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr($invoice->customer->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-gray-800 dark:text-white">{{ $invoice->customer->name ?? 'Umum' }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->vehicle->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800 dark:text-white">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' }}">
                            {{ $invoice->status == 'paid' ? 'LUNAS' : 'BON' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                             <a href="{{ route('invoices.show', $invoice) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Print">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="bi bi-receipt text-4xl mb-3 block opacity-30"></i>
                        Belum ada invoice.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
