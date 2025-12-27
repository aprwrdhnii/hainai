@extends('layouts.app')
@section('title', 'Daftar Promo')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Promo & Diskon</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola program promosi untuk pelanggan</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('promos.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Promo
        </a>
    </div>
</div>

<!-- Promos Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($promos as $promo)
    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:border-orange-200 dark:hover:border-orange-900/50 transition relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-100 dark:bg-orange-900/20 rounded-full blur-xl group-hover:bg-orange-200 dark:group-hover:bg-orange-900/30 transition"></div>
        
        <div class="p-6 relative z-10">
            <div class="flex justify-between items-start mb-4">
                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $promo->status == 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ $promo->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                </span>
                <div class="flex gap-1">
                    <a href="{{ route('promos.edit', $promo) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-2">{{ $promo->name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">{{ $promo->description }}</p>
            
            <div class="flex items-center gap-2 mb-4">
                <span class="text-3xl font-black text-orange-600">
                    {{ $promo->discount_type == 'percentage' ? $promo->discount_value . '%' : 'Rp ' . number_format($promo->discount_value, 0, ',', '.') }}
                </span>
                <span class="text-xs font-medium text-gray-400 uppercase">OFF</span>
            </div>

            <div class="pt-4 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1"><i class="bi bi-calendar"></i> {{ $promo->start_date->format('d M') }} - {{ $promo->end_date->format('d M Y') }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <i class="bi bi-ticket-perforated text-4xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada promo</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Buat promo menarik untuk pelanggan Anda.</p>
        <a href="{{ route('promos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
            <i class="bi bi-plus-lg"></i> Buat Promo
        </a>
    </div>
    @endforelse
</div>

@if($promos->hasPages())
<div class="mt-6">
    {{ $promos->links() }}
</div>
@endif
@endsection
