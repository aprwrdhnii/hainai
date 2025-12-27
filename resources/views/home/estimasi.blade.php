@extends('layouts.public')
@section('title', 'Kira-kira Biaya')

@section('content')
<!-- Header -->
<section class="bg-gray-900 pt-32 pb-20 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
        <span class="text-orange-500 font-bold tracking-wider text-sm uppercase mb-2 block">Kalkulator Servis</span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Cek Estimasi Biaya</h1>
        <p class="text-gray-400 max-w-2xl mx-auto text-lg">Hitung parkiraan biaya servis motor pian sabelum ka bengkel. Transparan, kada ada biaya siluman.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-10 relative z-20 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calculator Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-xl border border-gray-100" x-data="estimasiCalculator()">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 text-xl">
                    <i class="bi bi-calculator-fill"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Hitung Biaya</h2>
                    <p class="text-gray-500 text-sm">Pilih layanan dan onderdil yang diparlukan</p>
                </div>
            </div>
            
            <!-- Service Selection -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Paket Servis</label>
                <div class="relative">
                    <i class="bi bi- wrench absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select x-model="selectedPackage" @change="calculate()" 
                        class="w-full pl-11 pr-4 py-4 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition appearance-none cursor-pointer">
                        <option value="">-- Pilih Paket Servis --</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->price }}">{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Spareparts Selection -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-3">Tambah Onderdil (Opsional)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($spareparts as $sparepart)
                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition select-none group"
                        :class="selectedSpareparts.includes('{{ $sparepart->id }}') ? 'border-orange-500 bg-orange-50' : 'border-gray-100 hover:border-orange-200 hover:bg-gray-50'">
                        <input type="checkbox" x-model="selectedSpareparts" value="{{ $sparepart->id }}" 
                            data-price="{{ $sparepart->sell_price }}" @change="calculate()"
                            class="peer sr-only">
                        
                        <div class="flex-1">
                            <span class="block font-semibold text-gray-900 mb-1">{{ $sparepart->name }}</span>
                            <span class="block text-sm text-gray-500 group-hover:text-orange-600 transition">Rp {{ number_format($sparepart->sell_price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center text-white transition">
                            <i class="bi bi-check text-xs opacity-0 peer-checked:opacity-100"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <!-- Result Bar -->
            <div class="bg-gray-900 rounded-2xl p-6 text-white flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Total Estimasi Biaya</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg">Rp</span>
                        <span class="text-4xl font-bold tracking-tight" x-text="total.toLocaleString('id-ID')">0</span>
                    </div>
                </div>
                
                <a href="https://wa.me/{{ config('services.whatsapp.boss_phone') }}?text=Halo, ulun handak servis motor dengan kira-kira Rp " 
                   x-bind:href="'https://wa.me/{{ config('services.whatsapp.boss_phone') }}?text=Halo, ulun handak servis motor dengan kira-kira Rp ' + total.toLocaleString('id-ID')"
                   target="_blank"
                   class="w-full md:w-auto px-8 py-4 bg-orange-500 hover:bg-orange-600 rounded-xl font-bold flex items-center justify-center gap-2 transition shadow-lg shadow-orange-500/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                   :class="{'pointer-events-none opacity-50': total === 0}">
                    <i class="bi bi-whatsapp"></i> Booking via WA
                </a>
            </div>
            <p class="text-xs text-gray-400 mt-4 text-center">* Harga bisa berubah sesuai kondisi kerusakan aktual</p>
        </div>
        
        <!-- Info Sidebar -->
        <div class="space-y-6">
            <h3 class="font-bold text-gray-900 text-lg">Paket Populer</h3>
            
            @foreach($packages->take(3) as $package)
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:border-orange-500 transition group">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-bold text-gray-800 text-lg group-hover:text-orange-600 transition">{{ $package->name }}</h4>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $package->duration ?? 'Express' }}</span>
                </div>
                <p class="text-gray-500 text-sm mb-6">{{ $package->description }}</p>
                <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                    <span class="text-xs text-gray-400">Mulai dari</span>
                    <span class="font-bold text-xl text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
            
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
                <i class="bi bi-info-circle-fill text-2xl mb-4 block opacity-50"></i>
                <h4 class="font-bold text-lg mb-2">Perlu Bantuan?</h4>
                <p class="text-blue-100 text-sm mb-4">Jika pian bingung menentukan kerusakan motor, konsultasi gratis via WA.</p>
                <a href="https://wa.me/{{ config('services.whatsapp.boss_phone') }}" class="text-sm font-semibold hover:text-white text-blue-100 flex items-center gap-2">
                    Hubungi Admin <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function estimasiCalculator() {
    return {
        selectedPackage: '',
        selectedSpareparts: [],
        total: 0,
        sparepartPrices: {
            @foreach($spareparts as $sparepart)
            {{ $sparepart->id }}: {{ $sparepart->sell_price }},
            @endforeach
        },
        calculate() {
            let packagePrice = parseInt(this.selectedPackage) || 0;
            let sparepartTotal = 0;
            
            this.selectedSpareparts.forEach(id => {
                sparepartTotal += this.sparepartPrices[id] || 0;
            });
            
            this.total = packagePrice + sparepartTotal;
        }
    }
}
</script>
<style>
    /* Custom Scrollbar for list */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }
</style>
@endpush
@endsection
