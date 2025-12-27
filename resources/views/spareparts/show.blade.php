@extends('layouts.app')
@section('title', 'Detail Sparepart')

@section('content')
<div class="mb-6">
    <a href="{{ route('spareparts.index') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>
</div>

<div class="max-w-2xl">
    <div class="card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="font-semibold text-gray-800 dark:text-white">Detail Sparepart</h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Kode</span>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">{{ $sparepart->code }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Nama</span>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $sparepart->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Kategori</span>
                        <p class="text-gray-800 dark:text-white">{{ $sparepart->category ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Stok</span>
                        <p class="font-medium {{ $sparepart->stock <= $sparepart->min_stock ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">
                            {{ $sparepart->stock }}
                            @if($sparepart->stock <= $sparepart->min_stock)
                                <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Low</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Stok Minimum</span>
                        <p class="text-gray-800 dark:text-white">{{ $sparepart->min_stock }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Harga Beli</span>
                        <p class="text-gray-800 dark:text-white">Rp {{ number_format($sparepart->buy_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Harga Jual</span>
                        <p class="font-medium text-green-600">Rp {{ number_format($sparepart->sell_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('spareparts.edit', $sparepart) }}" class="btn bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
