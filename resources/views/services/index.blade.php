@extends('layouts.app')
@section('title', 'Daftar Servis')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">
            @if(isset($mechanic) && $mechanic)
                Servis: {{ $mechanic->name }}
            @else
                Servis & Perbaikan
            @endif
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">
            @if(request('start_date') && request('end_date'))
                Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
            @else
                Kelola data servis kendaraan masuk
            @endif
        </p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('services.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Buat Servis
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 flex overflow-x-auto no-scrollbar">
    <a href="{{ route('services.index') }}" 
        class="flex-1 min-w-[100px] text-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all {{ !request('status') ? 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
        Semua
    </a>
    <a href="{{ route('services.index', ['status' => 'bon']) }}" 
        class="flex-1 min-w-[100px] text-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all {{ request('status') == 'bon' ? 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
        <i class="bi bi-receipt"></i> Bon
    </a>
    <a href="{{ route('services.index', ['status' => 'lunas']) }}" 
        class="flex-1 min-w-[100px] text-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all {{ request('status') == 'lunas' ? 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
        <i class="bi bi-check-circle"></i> Lunas
    </a>
</div>

<!-- Service List -->
<div class="grid gap-4">
    @forelse($services as $service)
    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-orange-200 dark:hover:border-orange-900/50 transition-all duration-300">
        <!-- Status Badge absolute -->
        <div class="absolute top-4 right-4">
            @if($service->status == 'bon')
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                    <i class="bi bi-clock-history mr-1.5"></i> Bon
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    <i class="bi bi-check-lg mr-1.5"></i> Lunas
                </span>
            @endif
        </div>

        <a href="{{ route('services.show', $service) }}" class="block">
            <div class="flex justify-between items-start mb-4 pr-20"> <!-- padding right for badge -->
                <div>
                    <span class="font-mono text-xs font-bold text-orange-500 bg-orange-50 dark:bg-orange-900/20 px-2 py-1 rounded mb-2 inline-block">
                        {{ $service->service_number }}
                    </span>
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white group-hover:text-orange-600 transition truncate max-w-[200px] sm:max-w-md">
                        {{ $service->vehicle->name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $service->vehicle->customer->name }} • {{ $service->vehicle->customer->phone ?? 'Tanpa No HP' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-700/50">
                <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1.5" title="Montir">
                        <i class="bi bi-person-gear"></i>
                        <span>{{ $service->mechanic->name ?? '-' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-1.5" title="Tanggal">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ $service->service_date->format('d/m') }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 mb-0.5">Total Biaya</p>
                    <p class="font-bold text-gray-800 dark:text-white text-lg">Rp {{ number_format($service->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </a>
    </div>
    @empty
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-inbox text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada servis</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Data servis yang Anda cari tidak ditemukan.</p>
        <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
            <i class="bi bi-plus-lg"></i> Buat Servis Baru
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($services->hasPages())
<div class="mt-6">
    {{ $services->links() }}
</div>
@endif
@endsection
