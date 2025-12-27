@extends('layouts.app')
@section('title', 'Buat Paket Servis')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Paket Servis</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Buat bundling layanan servis</p>
        </div>
        <a href="{{ route('service-packages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('service-packages.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Paket *</label>
                    <input type="text" name="name" placeholder="Contoh: Paket Servis Ringan + Oli" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition placeholder-gray-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Harga (Rp) *</label>
                        <input type="number" name="price" placeholder="0" required min="0"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition placeholder-gray-400 font-bold text-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Diskon (Opsional)</label>
                        <input type="number" name="discount" placeholder="0" min="0" value="0"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition placeholder-gray-400">
                        <p class="text-xs text-gray-500 mt-1">Nilai potongan (jika ada) untuk ditampilkan sebagai harga coret.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan secara singkat tentang paket ini..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition placeholder-gray-400"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Fitur / Item Paket (Pisahkan dengan koma)</label>
                    <textarea name="features" rows="4" placeholder="Ganti Oli Mesin,&#10;Service Karburator,&#10;Cek Rem,&#10;Cuci Motor"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition placeholder-gray-400"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="reset" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold shadow-lg shadow-orange-600/20 transition flex items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
