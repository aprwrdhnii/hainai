@extends('layouts.app')
@section('title', 'Detail Kendaraan')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-600 transition">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sidebar: Vehicle Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 text-center">
                <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/40 dark:to-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-4xl mx-auto mb-4 shadow-inner">
                    <i class="bi bi-bicycle"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $vehicle->name }}</h2>
                <div class="flex items-center justify-center gap-2 mt-2 text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-1 text-sm mx-auto w-fit">
                    {{ $vehicle->plate_number ?? 'NON-PLAT' }}
                </div>
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="flex-1 py-2 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition shadow-lg shadow-orange-600/20">
                        Edit
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-bold text-gray-800 dark:text-white mb-4">Milik Pelanggan</h4>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold">
                        {{ strtoupper(substr($vehicle->customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('customers.show', $vehicle->customer) }}" class="font-bold text-gray-800 dark:text-white hover:text-orange-600 transition display-block">
                            {{ $vehicle->customer->name }}
                        </a>
                        <p class="text-xs text-gray-500">{{ $vehicle->customer->phone ?? 'Tanpa No HP' }}</p>
                    </div>
                </div>
            </div>

            @if($vehicle->notes)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-bold text-gray-800 dark:text-white mb-2">Catatan</h4>
                <p class="text-gray-600 dark:text-gray-400 text-sm italic">
                    "{{ $vehicle->notes }}"
                </p>
            </div>
            @endif
        </div>

        <!-- Main Content: Service History -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-clock-history text-orange-500"></i> Riwayat Servis
                    </h3>
                    <a href="{{ route('services.create', ['vehicle_id' => $vehicle->id]) }}" class="text-sm bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 px-3 py-1.5 rounded-lg font-medium hover:bg-orange-100 dark:hover:bg-orange-900/40 transition">
                        + Servis Baru
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($vehicle->services as $service)
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
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">
                            {{ $service->complaint ?? 'Tidak ada keluhan tercatat.' }}
                        </p>
                        <div class="flex items-center justify-between mt-3 text-xs text-gray-500">
                             <div class="flex gap-3">
                                <span class="flex items-center gap-1"><i class="bi bi-wrench"></i> {{ $service->mechanic->name ?? 'Admin' }}</span>
                                <span class="flex items-center gap-1"><i class="bi bi-cash"></i> Rp {{ number_format($service->total, 0, ',', '.') }}</span>
                             </div>
                             <a href="{{ route('services.show', $service) }}" class="text-orange-600 hover:underline">Lihat Detail</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Belum ada riwayat servis untuk kendaraan ini.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
