@extends('layouts.app')
@section('title', 'Laporan Pendapatan')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('reports.index') }}" class="hover:text-orange-600 transition"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span>/</span>
            <span>Keuangan</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Pendapatan</h2>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        
        <!-- Period Selector -->
        <div class="flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1 shrink-0">
            <button type="submit" name="period" value="daily" class="px-4 py-2 rounded-lg text-sm font-bold transition {{ ($period ?? 'daily') == 'daily' ? 'bg-white dark:bg-gray-600 text-orange-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">
                Harian
            </button>
            <button type="submit" name="period" value="monthly" class="px-4 py-2 rounded-lg text-sm font-bold transition {{ ($period ?? '') == 'monthly' ? 'bg-white dark:bg-gray-600 text-orange-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">
                Bulanan
            </button>
            <button type="submit" name="period" value="yearly" class="px-4 py-2 rounded-lg text-sm font-bold transition {{ ($period ?? '') == 'yearly' ? 'bg-white dark:bg-gray-600 text-orange-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">
                Tahunan
            </button>
        </div>

        <!-- Date Inputs -->
        <div class="flex-1 flex gap-2">
            @if(($period ?? 'daily') == 'daily')
            <input type="date" name="date" value="{{ $startDate }}" onchange="this.form.submit()"
                class="w-full md:max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
            @elseif($period == 'monthly')
            <input type="month" name="month" value="{{ date('Y-m', strtotime($startDate)) }}" onchange="this.form.submit()"
                class="w-full md:max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
            @else
            <select name="year" onchange="this.form.submit()" class="w-full md:max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ date('Y', strtotime($startDate)) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            @endif
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Revenue -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-3xl p-6 text-white shadow-lg shadow-green-500/20 relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-green-100 font-medium text-sm mb-1 uppercase tracking-wider">Pendapatan Bersih (Lunas)</p>
            <h3 class="text-3xl font-black">Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-sm font-medium bg-white/20 w-fit px-3 py-1 rounded-lg backdrop-blur-sm">
                 <i class="bi bi-check-circle-fill mr-2"></i> {{ $summary['lunas_count'] }} Transaksi Lunas
            </div>
        </div>
        <i class="bi bi-wallet2 absolute -right-6 -bottom-6 text-9xl text-white/10"></i>
    </div>

    <!-- Pending (Bon) -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg shadow-orange-500/20 relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-orange-100 font-medium text-sm mb-1 uppercase tracking-wider">Piutang / Bon</p>
            <h3 class="text-3xl font-black">Rp {{ number_format($summary['total_bon'], 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-sm font-medium bg-white/20 w-fit px-3 py-1 rounded-lg backdrop-blur-sm">
                 <i class="bi bi-clock-history mr-2"></i> {{ $summary['bon_count'] }} Transaksi Belum Lunas
            </div>
        </div>
        <i class="bi bi-receipt absolute -right-6 -bottom-6 text-9xl text-white/10"></i>
    </div>

    <!-- Total -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
        <div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mb-1 uppercase tracking-wider">Total Omzet (Gross)</p>
            <h3 class="text-3xl font-black text-gray-800 dark:text-white">Rp {{ number_format($summary['pendapatan'] + $summary['total_bon'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500 mt-2">akumulasi lunas & bon</p>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400 font-medium">Total Servis</span>
            <span class="text-xl font-bold text-gray-800 dark:text-white">{{ $summary['total_service'] }} Unit</span>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Rincian Transaksi</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 font-bold tracking-wider">
                    <th class="px-6 py-4">ID / Tanggal</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-gray-700 dark:text-gray-300 block">#{{ $service->service_number }}</span>
                        <span class="text-xs text-gray-500">{{ $service->service_date->format('d M Y, H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-sm text-gray-800 dark:text-white">{{ $service->vehicle->customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $service->vehicle->name }}</p>
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
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="bi bi-inbox text-4xl mb-3 block opacity-30"></i>
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
