@extends('layouts.app')
@section('title', 'Laporan & Statistik')

@section('content')
<!-- Header -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">Pusat Laporan</h2>
    <p class="text-gray-500 dark:text-gray-400">Analisis kinerja bisnis, keuangan, dan operasional bengkel Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Revenue Report -->
    <a href="{{ route('reports.revenue') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/50 hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 rounded-2xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center font-bold text-3xl mb-6 shadow-inner group-hover:scale-110 transition">
            <i class="bi bi-cash-stack"></i>
        </div>
        <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-2 group-hover:text-orange-600 transition">Pendapatan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Laporan keuangan, omzet harian, bulanan, dan tahunan.
        </p>
        <div class="mt-6 flex items-center text-sm font-bold text-orange-600 dark:text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-10px] group-hover:translate-x-0 duration-300">
            Lihat Laporan <i class="bi bi-arrow-right ml-2"></i>
        </div>
    </a>

    <!-- Services Report -->
    <a href="{{ route('reports.services') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/50 hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-3xl mb-6 shadow-inner group-hover:scale-110 transition">
            <i class="bi bi-wrench"></i>
        </div>
        <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-2 group-hover:text-orange-600 transition">Riwayat Servis</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Data servis kendaraan, status pengerjaan, dan keluhan.
        </p>
        <div class="mt-6 flex items-center text-sm font-bold text-orange-600 dark:text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-10px] group-hover:translate-x-0 duration-300">
            Lihat Laporan <i class="bi bi-arrow-right ml-2"></i>
        </div>
    </a>

    <!-- Inventory Report -->
    <a href="{{ route('reports.inventory') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/50 hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 rounded-2xl bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 flex items-center justify-center font-bold text-3xl mb-6 shadow-inner group-hover:scale-110 transition">
            <i class="bi bi-box-seam"></i>
        </div>
        <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-2 group-hover:text-orange-600 transition">Stok Onderdil</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Pergerakan stok, barang terlaris, dan stok menipis.
        </p>
        <div class="mt-6 flex items-center text-sm font-bold text-orange-600 dark:text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-10px] group-hover:translate-x-0 duration-300">
            Lihat Laporan <i class="bi bi-arrow-right ml-2"></i>
        </div>
    </a>

    <!-- Mechanics Report -->
    <a href="{{ route('reports.mechanics') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/50 hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-3xl mb-6 shadow-inner group-hover:scale-110 transition">
            <i class="bi bi-person-gear"></i>
        </div>
        <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-2 group-hover:text-orange-600 transition">Kinerja Montir</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
             Produktivitas montir, jumlah pekerjaan, dan komisi.
        </p>
        <div class="mt-6 flex items-center text-sm font-bold text-orange-600 dark:text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-10px] group-hover:translate-x-0 duration-300">
            Lihat Laporan <i class="bi bi-arrow-right ml-2"></i>
        </div>
    </a>

</div>
@endsection
