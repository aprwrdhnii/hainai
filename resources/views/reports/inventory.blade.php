@extends('layouts.app')
@section('title', 'Laporan Stok Onderdil')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('reports.index') }}" class="hover:text-orange-600 transition"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span>/</span>
            <span>Stok</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Stok Onderdil</h2>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mb-1">Total Aset (Harga Beli)</p>
        <h3 class="text-2xl font-black text-gray-800 dark:text-white">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mb-1">Total Item</p>
        <h3 class="text-2xl font-black text-gray-800 dark:text-white">{{ $totalItems }} <span class="text-sm font-medium text-gray-400">Jenis Barang</span></h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mb-1">Perlu Restock</p>
        <h3 class="text-2xl font-black text-red-600">{{ $lowStockItems }} <span class="text-sm font-medium text-red-400">Items</span></h3>
    </div>
</div>

<!-- Low Stock Table (Priority) -->
@if($lowStock->count() > 0)
<div class="bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/30 overflow-hidden mb-8">
    <div class="p-6 border-b border-red-100 dark:border-red-900/30 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <h3 class="font-bold text-lg text-red-900 dark:text-red-400">Peringatan Stok Menipis</h3>
            <p class="text-sm text-red-700 dark:text-red-300">Segera lakukan restock untuk barang-barang berikut.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-red-100/50 dark:bg-red-900/20 text-red-800 dark:text-red-300 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-3">Nama Barang</th>
                    <th class="px-6 py-3 text-center">Sisa Stok</th>
                    <th class="px-6 py-3 text-center">Min. Stok</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-100 dark:divide-red-900/30">
                @foreach($lowStock as $item)
                <tr>
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $item->name }}</td>
                    <td class="px-6 py-3 text-center font-bold text-red-600">{{ $item->stock }}</td>
                    <td class="px-6 py-3 text-center text-gray-500">{{ $item->min_stock }}</td>
                    <td class="px-6 py-3 text-right">
                        <a href="{{ route('spareparts.index', ['search' => $item->name]) }}" class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition font-bold">
                            Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- All Inventory -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Semua Stok Barang</h3>
         <a href="{{ route('spareparts.index') }}" class="text-sm text-orange-600 font-bold hover:underline">Kelola Stok <i class="bi bi-arrow-right"></i></a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 font-bold tracking-wider">
                    <th class="px-6 py-4">Nama Barang</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok</th>
                    <th class="px-6 py-4 text-right">Nilai Aset (Beli)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($inventory as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->code }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->category)
                        <span class="inline-block px-2 py-0.5 rounded text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $item->category }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                         <span class="font-bold {{ $item->stock <= $item->min_stock ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">{{ $item->stock }}</span>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300 font-mono text-sm">
                        Rp {{ number_format($item->buy_price * $item->stock, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data stok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
     @if($inventory->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $inventory->links() }}
    </div>
    @endif
</div>
@endsection
