@extends('layouts.app')
@section('title', 'Ringkasan Barata')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl p-8 mb-8 text-white relative overflow-hidden shadow-xl">
    <div class="relative z-10">
        <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-gray-400 max-w-xl">Ini ringkasan aktivitas bengkel hari ini. Jangan lupa cek stok onderdil nang menipis lah.</p>
        
        <div class="flex gap-3 mt-6">
            <a href="{{ route('services.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-orange-500/30 transition flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Buat Servis Baru
            </a>
            <button onclick="document.getElementById('waModal').classList.remove('hidden'); document.getElementById('waModal').classList.add('flex');" class="bg-white/10 hover:bg-white/20 text-white px-6 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition flex items-center gap-2">
                <i class="bi bi-whatsapp"></i> Kirim Laporan
            </button>
        </div>
    </div>
    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-orange-500/20 to-transparent"></div>
    <i class="bi bi-rocket-takeoff-fill absolute -bottom-4 -right-4 text-9xl text-white/5 rotate-[-45deg]"></i>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue Today -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center">
                <i class="bi bi-cash-stack text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg">+Hari Ini</span>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Pandapatan Hari Ini</p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</h3>
    </div>

    <!-- Active Services -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center">
                <i class="bi bi-wrench-adjustable text-orange-600 dark:text-orange-400 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Belum Lunas</span>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Servis Aktif (Bon)</p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['services_bon'] }} <span class="text-sm font-normal text-gray-400">Unit</span></h3>
    </div>

    <!-- Customers -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                <i class="bi bi-people-fill text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Total Pelanggan</p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['customers'] }} <span class="text-sm font-normal text-gray-400">Orang</span></h3>
    </div>

    <!-- Low Stock -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        @if($stats['low_stock'] > 0)
        <div class="absolute inset-0 bg-red-500/10 animate-pulse"></div>
        @endif
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 {{ $stats['low_stock'] > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100' }} rounded-2xl flex items-center justify-center">
                    <i class="bi bi-box-seam text-{{ $stats['low_stock'] > 0 ? 'red' : 'gray' }}-600 text-xl"></i>
                </div>
                @if($stats['low_stock'] > 0)
                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Perlu Restock!</span>
                @endif
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Stok Menipis</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['low_stock'] }} <span class="text-sm font-normal text-gray-400">Item</span></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Services Table -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Servis Tahanyar</h3>
            <a href="{{ route('services.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-left">
                    <tr>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase">ID Servis</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase">Pelanggan & Motor</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase">Montir</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentServices as $service)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-medium text-orange-600">{{ $service->service_number }}</span>
                            <p class="text-xs text-gray-400">{{ $service->created_at->format('d M, H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 dark:text-white">{{ $service->vehicle->customer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $service->vehicle->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($service->mechanic->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $service->mechanic->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($service->status == 'bon')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-600 mr-1.5"></span>
                                    Bon / Proses
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span>
                                    Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 dark:text-white">
                            Rp {{ number_format($service->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="bi bi-inbox text-3xl mb-3 block opacity-50"></i>
                            Belum ada data servis hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats / Side -->
    <div class="space-y-6">
        <!-- Revenue Month -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg">
            <div class="flex items-center gap-3 mb-4 opacity-80">
                <i class="bi bi-calendar-check"></i>
                <span class="text-sm font-medium">Bulan Ini ({{ date('F') }})</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</h3>
            <p class="text-blue-200 text-sm">Total Omset Kotor</p>
        </div>

        <!-- Receivables (Total Bon) -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-bold text-gray-800 dark:text-white mb-4">Total Piutang (Bon)</h4>
            <div class="flex items-end gap-2 mb-2">
                <h3 class="text-3xl font-bold text-orange-600">Rp {{ number_format($stats['total_bon'], 0, ',', '.') }}</h3>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Dari {{ $stats['services_bon'] }} transaksi yang belum lunas.</p>
            
            <a href="{{ route('services.index', ['status' => 'bon']) }}" class="mt-4 w-full block text-center py-2.5 rounded-xl bg-orange-50 text-orange-600 font-semibold text-sm hover:bg-orange-100 transition">
                Tagih Sekarang
            </a>
        </div>
    </div>
</div>

<!-- WhatsApp Modal (Preserved Functionality) -->
<div id="waModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[100] p-4" onclick="if(event.target === this) { this.classList.add('hidden'); this.classList.remove('flex'); }">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 w-full max-w-md shadow-2xl transform scale-100 transition-all">
        <div class="flex justify-between items-center mb-6">
            <h5 class="font-bold text-xl text-gray-800 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center"><i class="bi bi-whatsapp text-green-600"></i></span>
                Kirim Laporan
            </h5>
            <button onclick="document.getElementById('waModal').classList.add('hidden'); document.getElementById('waModal').classList.remove('flex');" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('reports.send-whatsapp') }}" method="POST" target="_blank">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor WA Bos</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400 font-bold">+</span>
                    <input type="text" name="phone" value="{{ config('services.whatsapp.boss_phone') }}" placeholder="628123456789"
                        class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition font-medium">
                </div>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6 border border-blue-100 dark:border-blue-800">
                <p class="text-blue-800 dark:text-blue-200 text-sm font-medium mb-2">Laporan mencakup:</p>
                <ul class="text-blue-600 dark:text-blue-300 text-xs space-y-1.5 list-disc pl-4">
                    <li>Ringkasan omset hari ini & bulan ini</li>
                    <li>Sisa stok yang perlu dibelanja</li>
                    <li>Total uang kas vs piutang (bon)</li>
                </ul>
            </div>
            
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3.5 rounded-xl font-bold text-lg shadow-lg shadow-green-500/20 transition flex items-center justify-center gap-2">
                <i class="bi bi-send-fill"></i> Kirim Laporan
            </button>
        </form>
    </div>
</div>
@endsection
