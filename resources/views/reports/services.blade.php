@extends('layouts.app')
@section('title', 'Laporan Riwayat Servis')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('reports.index') }}" class="hover:text-orange-600 transition"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span>/</span>
            <span>Servis</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Riwayat Servis</h2>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 flex gap-2">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                class="w-full md:max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
             <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <option value="">Semua Status</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="bon" {{ request('status') == 'bon' ? 'selected' : '' }}>Bon</option>
            </select>
        </div>
    </form>
</div>

<!-- Services List -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Daftar Servis Bulanan</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 font-bold tracking-wider">
                    <th class="px-6 py-4">Tanggal / No. Servis</th>
                    <th class="px-6 py-4">Pelanggan & Kendaraan</th>
                    <th class="px-6 py-4">Montir</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Total Biaya</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                         <span class="text-xs text-gray-500">{{ $service->created_at->format('d/m/Y H:i') }}</span>
                        <span class="font-mono font-bold text-gray-700 dark:text-gray-300 block text-sm mt-0.5">#{{ $service->service_number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-sm text-gray-800 dark:text-white">{{ $service->vehicle->customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $service->vehicle->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 text-xs flex items-center justify-center font-bold">
                                {{ substr($service->mechanic->name ?? 'A', 0, 1) }}
                            </span>
                            <span class="text-sm">{{ $service->mechanic->name ?? 'Admin' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $service->status == 'lunas' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' }}">
                            {{ $service->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-bold text-gray-800 dark:text-white">Rp {{ number_format($service->total, 0, ',', '.') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data servis pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($services->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection
