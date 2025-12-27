@extends('layouts.app')
@section('title', 'WhatsApp Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">WhatsApp Gateway</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Integrasi Fonnte untuk notifikasi otomatis</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="space-y-6">
        <!-- Status Connection -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="bi bi-whatsapp text-6xl text-green-500"></i>
            </div>
            
            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-router text-orange-600"></i> Status Koneksi
            </h3>

            @if($status['isReady'])
            <div class="flex items-center gap-4 bg-green-50 dark:bg-green-900/10 p-4 rounded-2xl border border-green-100 dark:border-green-900/30">
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                    <i class="bi bi-check-lg text-2xl font-bold"></i>
                </div>
                <div>
                    <h4 class="font-bold text-green-800 dark:text-green-400">Terhubung</h4>
                    <p class="text-sm text-green-600 dark:text-green-300">Device siap mengirim pesan.</p>
                </div>
            </div>
            @else
            <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-2xl border border-red-100 dark:border-red-900/30 mb-6">
                 <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                        <i class="bi bi-x-lg text-2xl font-bold"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-800 dark:text-red-400">Terputus</h4>
                        <p class="text-sm text-red-600 dark:text-red-300">{{ $status['statusMessage'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                    <p class="font-bold text-gray-800 dark:text-white text-sm mb-2">Langkah Perbaikan:</p>
                    <ol class="list-decimal list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>Login ke dashboard <a href="https://fonnte.com" target="_blank" class="text-blue-600 hover:underline font-bold">Fonnte.com</a></li>
                        <li>Scan QR Code pada menu Devices</li>
                        <li>Pastikan Token API di file <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded font-mono text-xs">.env</code> sudah benar</li>
                    </ol>
                </div>
            </div>
            @endif
        </div>

        <!-- Connection Settings is implicitly handled via ENV, so we focus on Actions -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Test Sender -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="bi bi-send text-orange-600"></i> Tes Kirim Pesan
                </h3>
                <form action="{{ route('admin.whatsapp.test') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Tujuan</label>
                        <input type="text" name="phone" value="{{ $bossPhone }}" placeholder="628..." required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pesan Test</label>
                        <textarea name="message" rows="2" placeholder="Halo, ini tes..." required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition text-sm">Tes koneksi WhatsApp dari Admin Panel.</textarea>
                    </div>
                    <button type="submit" class="w-full btn bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold py-3 rounded-xl hover:opacity-90 transition shadow-lg">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Manual Report Trigger -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-text text-orange-600"></i> Laporan Harian
                </h3>
                
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Kirim ringkasan laporan pendapatan dan aktivitas hari ini secara manual ke nomor Owner.
                    </p>
                    
                    <div class="flex items-center gap-2 text-xs text-orange-600 bg-orange-50 dark:bg-orange-900/10 p-3 rounded-xl mb-6">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Laporan otomatis dijadwalkan setiap pukul 18:00 WIB.</span>
                    </div>
                </div>

                <form action="{{ route('reports.send-whatsapp') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Tujuan Laporan</label>
                        <input type="text" name="phone" value="{{ $bossPhone }}" placeholder="628..." required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition text-sm">
                    </div>
                    <button type="submit" class="w-full btn bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-green-500/20 flex items-center justify-center gap-2">
                        <i class="bi bi-whatsapp"></i> Kirim Laporan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
