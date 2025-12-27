@extends('layouts.app')
@section('title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-600 transition">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sidebar: Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/40 dark:to-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold text-3xl mx-auto mb-4 shadow-inner">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $customer->name }}</h2>
                <div class="flex items-center justify-center gap-2 mt-2 text-gray-500 dark:text-gray-400 text-sm">
                    <i class="bi bi-whatsapp"></i> {{ $customer->phone ?? 'Tidak ada nomor' }}
                </div>
                @if($customer->address)
                <p class="mt-4 text-sm text-gray-500 italic px-4">
                    "{{ $customer->address }}"
                </p>
                @endif
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="flex-1 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Edit
                    </a>
                    @if($customer->phone)
                    <a href="https://wa.me/{{ $customer->phone }}" target="_blank" class="flex-1 py-2 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 font-medium hover:bg-green-200 dark:hover:bg-green-900/50 transition">
                        Chat
                    </a>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-bold text-gray-800 dark:text-white mb-4">Statistik</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Total Kendaraan</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $customer->vehicles->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Vehicles & History -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Vehicles List -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-car-front text-blue-500"></i> Kendaraan Terdaftar
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($customer->vehicles as $vehicle)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-bicycle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $vehicle->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $vehicle->plate_number ?? 'Tanpa Plat' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-gray-400 hover:text-orange-500 transition">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500">
                        Belum ada kendaraan terdaftar.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
