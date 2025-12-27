@extends('layouts.app')
@section('title', 'Detail Montir')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('mechanics.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-600 transition">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sidebar: Mechanic Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900/40 dark:to-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-3xl mx-auto mb-4 shadow-inner">
                    {{ strtoupper(substr($mechanic->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $mechanic->name }}</h2>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $mechanic->status == 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $mechanic->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                    </span>
                </div>
                
                @if($mechanic->phone)
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
                    <i class="bi bi-whatsapp"></i> {{ $mechanic->phone }}
                </div>
                @endif

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('mechanics.edit', $mechanic) }}" class="flex-1 py-2 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition shadow-lg shadow-orange-600/20">
                        Edit
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-bold text-gray-800 dark:text-white mb-4">Statistik</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Total Servis</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $mechanic->services->count() }}</span>
                    </div>
                    @if($mechanic->services->count() > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Total Pendapatan Jasa</span>
                        <span class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($mechanic->services->sum('labor_cost'), 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Recent Jobs -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-wrench-adjustable text-purple-500"></i> Pekerjaan Terakhir
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($mechanic->services->take(10) as $service)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition group">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-xs font-bold text-gray-400">#{{ $service->service_number }}</span>
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $service->created_at->format('d M Y') }}</h4>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $service->status == 'lunas' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' }}">
                                {{ strtoupper($service->status) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-2">
                             <i class="bi bi-bicycle"></i> {{ $service->vehicle->name }} - {{ $service->vehicle->customer->name }}
                        </div>
                        <div class="flex items-center justify-between mt-3 text-xs text-gray-500">
                             <span class="flex items-center gap-1"><i class="bi bi-cash"></i> Jasa: Rp {{ number_format($service->labor_cost, 0, ',', '.') }}</span>
                             <a href="{{ route('services.show', $service) }}" class="text-orange-600 hover:underline">Lihat Detail</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Belum ada pekerjaan yang tercatat.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
