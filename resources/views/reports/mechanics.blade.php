@extends('layouts.app')
@section('title', 'Laporan Kinerja Montir')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('reports.index') }}" class="hover:text-orange-600 transition"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span>/</span>
            <span>Montir</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Kinerja Montir</h2>
    </div>
</div>

<!-- Period Filter -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 flex gap-2">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                class="w-full md:max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
        </div>
    </form>
</div>

<!-- Mechanics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($mechanics as $mechanic)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
        <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700 bg-gradient-to-b from-gray-50/50 to-transparent dark:from-gray-700/20">
            <div class="w-20 h-20 mx-auto bg-white dark:bg-gray-700 rounded-full border-4 border-white dark:border-gray-600 shadow-md flex items-center justify-center mb-3">
                <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ substr($mechanic->name, 0, 1) }}</span>
            </div>
            <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-1">{{ $mechanic->name }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $mechanic->specialization ?? 'Mechanic' }}</p>
        </div>
        
        <div class="p-6 grid grid-cols-2 gap-4 text-center">
            <div>
                <p class="text-xs text-gray-500 mb-1">Total Servis</p>
                <h4 class="text-2xl font-black text-gray-800 dark:text-white">{{ $mechanic->services_count }}</h4>
                <p class="text-[10px] text-gray-400">Unit Kendaraan</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Total Jasa</p>
                <h4 class="text-xl font-bold text-green-600">Rp {{ number_format($mechanic->total_labor, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-gray-400">Pendapatan</p>
            </div>
        </div>
        
        <div class="p-3 bg-gray-50 dark:bg-gray-900/30 text-center">
             <a href="{{ route('services.index', ['mechanic_id' => $mechanic->id]) }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 uppercase tracking-wider">
                Lihat Detail Pekerjaan <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <p class="text-gray-500">Tidak ada data montir.</p>
    </div>
    @endforelse
</div>
@endsection
