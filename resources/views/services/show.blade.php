@extends('layouts.app')
@section('title', 'Detail Servis')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Back -->
    <div class="mb-6">
        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-600 transition">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Status Banner -->
            <div class="relative overflow-hidden rounded-2xl p-6 {{ $service->status == 'bon' ? 'bg-orange-500 text-white' : 'bg-green-500 text-white' }} shadow-lg">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-white/80 font-medium text-sm mb-1 uppercase tracking-wider">Status Servis</p>
                        <h2 class="text-3xl font-bold mb-2">{{ $service->status == 'bon' ? 'BELUM LUNAS (BON)' : 'LUNAS' }}</h2>
                        <p class="text-white/90 text-sm">
                            No. Servis: <span class="font-mono font-bold">{{ $service->service_number }}</span>
                        </p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <i class="bi {{ $service->status == 'bon' ? 'bi-receipt' : 'bi-check-circle-fill' }} text-4xl"></i>
                    </div>
                </div>
                
                <!-- Action Buttons for Status -->
                @if($service->status == 'bon')
                <div class="relative z-10 mt-6 pt-6 border-t border-white/20">
                    <form action="{{ route('services.update-status', $service) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="lunas">
                        <button type="submit" class="w-full bg-white text-orange-600 font-bold py-3 rounded-xl hover:bg-orange-50 transition shadow-md flex items-center justify-center gap-2">
                            <i class="bi bi-cash-stack"></i> Tandai Lunas
                        </button>
                    </form>
                </div>
                @else
                <div class="relative z-10 mt-6 pt-6 border-t border-white/20 flex gap-2">
                    <a href="{{ route('invoices.print', $service) }}" target="_blank" class="flex-1 bg-white/20 hover:bg-white/30 text-white font-semibold py-2.5 rounded-xl transition flex items-center justify-center gap-2 backdrop-blur-sm">
                        <i class="bi bi-printer"></i> Cetak Nota
                    </a>
                    <form action="{{ route('services.update-status', $service) }}" method="POST" class="flex-none">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="bon">
                        <button type="submit" class="bg-white/10 hover:bg-white/20 text-white p-2.5 rounded-xl transition" onclick="return confirm('Ubah status kembali ke BON?')" title="Kembalikan ke Bon">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>
                </div>
                @endif
                
                <!-- Decorative Pattern -->
                <div class="absolute -right-10 -bottom-10 opacity-10 rotate-12">
                    <i class="bi bi-wrench-adjustable text-9xl"></i>
                </div>
            </div>

            <!-- Vehicle & Customer Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="bi bi-car-front text-blue-500"></i> Detail Kendaraan & Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kendaraan</p>
                        <p class="font-bold text-lg text-gray-800 dark:text-white">{{ $service->vehicle->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $service->vehicle->plate_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pelanggan</p>
                        <p class="font-bold text-lg text-gray-800 dark:text-white">{{ $service->vehicle->customer->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $service->vehicle->customer->phone ?? 'No HP -' }}</p>
                    </div>
                    <div class="md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Keluhan / Catatan</p>
                        <p class="text-gray-700 dark:text-gray-300 italic">"{{ $service->complaint ?? 'Tidak ada catatan' }}"</p>
                    </div>
                </div>
            </div>

            <!-- Spareparts List -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-box-seam text-orange-500"></i> Onderdil Digunakan
                    </h3>
                    <button onclick="document.getElementById('addPartModal').showModal()" class="text-sm bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 px-3 py-1.5 rounded-lg font-medium hover:bg-orange-100 dark:hover:bg-orange-900/40 transition">
                        + Tambah
                    </button>
                </div>
                
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($service->spareparts as $part)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                {{ $part->pivot->quantity }}x
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $part->name }}</p>
                                <p class="text-xs text-gray-500">@ Rp {{ number_format($part->pivot->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-gray-800 dark:text-white">Rp {{ number_format($part->pivot->subtotal, 0, ',', '.') }}</span>
                            <form action="{{ route('services.remove-part', [$service, $part]) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 p-1">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Belum ada onderdil yang ditambahkan.
                    </div>
                    @endforelse
                </div>
            </div>
            
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-6">
            <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-xl sticky top-24">
                <h3 class="font-bold text-lg mb-6 border-b border-gray-700 pb-4">Rincian Biaya</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-gray-300">
                        <span>Total Onderdil</span>
                        <span class="font-medium">Rp {{ number_format($service->spareparts->sum('pivot.subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-300">
                        <span>Biaya Jasa</span>
                        <span class="font-medium">Rp {{ number_format($service->labor_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 pt-4 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-gray-400 text-sm">Total Tagihan</span>
                        <span class="text-3xl font-bold text-orange-400">Rp {{ number_format($service->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('services.edit', $service) }}" class="block w-full text-center py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white font-medium transition">
                        Edit Servis
                    </a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data servis ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full text-center py-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-500 font-medium transition">
                            Hapus Servis
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-sm">Info Tambahan</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Montir</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $service->mechanic->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $service->service_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Waktu</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($service->service_time)->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Part Modal -->
<dialog id="addPartModal" class="modal rounded-2xl shadow-2xl p-0 w-full max-w-lg bg-white dark:bg-gray-800 backdrop:bg-gray-900/50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-800 dark:text-white">Tambah Onderdil</h3>
            <form method="dialog">
                <button class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </form>
        </div>
        
        <form action="{{ route('services.add-part', $service) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Onderdil</label>
                    <select name="sparepart_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" required>
                        @foreach($spareparts as $sp)
                        <option value="{{ $sp->id }}">{{ $sp->name }} (Stok: {{ $sp->stock }}) - Rp {{ number_format($sp->sell_price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah</label>
                    <div class="flex items-center">
                        <button type="button" onclick="this.parentNode.querySelector('input').stepDown()" class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-l-xl border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600">-</button>
                        <input type="number" name="qty" value="1" min="1" class="w-full h-10 border-y border-gray-300 dark:border-gray-600 text-center bg-white dark:bg-gray-800 dark:text-white" required>
                        <button type="button" onclick="this.parentNode.querySelector('input').stepUp()" class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-r-xl border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600">+</button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-600/20 transition mt-4">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection
