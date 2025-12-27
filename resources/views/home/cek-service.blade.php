@extends('layouts.public')
@section('title', 'Lacak Status Servis')

@section('content')
<!-- Hero Search -->
<section class="bg-gray-900 pt-32 pb-24 relative overflow-hidden">
    <!-- Abstract Shapes -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[50%] pt-[50%] bg-gradient-to-b from-orange-500/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute top-[20%] -left-[10%] w-[40%] pt-[40%] bg-gradient-to-b from-blue-500/10 to-transparent rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-3xl mx-auto px-4 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-orange-400 text-sm font-medium mb-8 backdrop-blur-sm">
            <span class="flex h-2 w-2 rounded-full bg-orange-500"></span>
            Real-time Tracking System
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Lacak Motor Pian</h1>
        <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto">Masukakan nomor servis atau nomor HP gasan malihati progress pengerjaan dan detail biaya.</p>

        <form method="GET" class="relative max-w-xl mx-auto group">
            <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl opacity-50 group-hover:opacity-100 blur transition duration-200"></div>
            <div class="relative flex bg-white rounded-xl shadow-2xl p-2">
                <div class="flex-1 flex items-center pl-4 py-2">
                    <i class="bi bi-search text-gray-400 text-xl mr-3"></i>
                    <input type="text" name="q" value="{{ request('q') }}" 
                        placeholder="Contoh: SRV-2023... atau 08xxxx" 
                        class="w-full bg-transparent border-none focus:ring-0 text-gray-800 placeholder-gray-400 text-lg"
                        autocomplete="off">
                </div>
                <button type="submit" class="bg-gray-900 text-white px-8 rounded-lg font-bold hover:bg-black transition flex items-center">
                    Cek
                </button>
            </div>
        </form>
        @if(!request('q'))
        <p class="mt-4 text-xs text-gray-500">Privasi aman. Data cuma kawa dilihati pakai nomor unik.</p>
        @endif
    </div>
</section>

<!-- Result Section -->
<section class="max-w-4xl mx-auto px-4 -mt-16 pb-20 relative z-20">
    @if($searched)
        @if($service)
        <!-- Service Receipt Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 animate-up">
            <!-- Status Ribbon -->
            <div class="bg-gradient-to-r {{ $service->status == 'lunas' ? 'from-green-500 to-emerald-600' : 'from-orange-500 to-red-600' }} p-1"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-3">
                <!-- Left Details -->
                <div class="md:col-span-2 p-8 border-r border-gray-50">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Nomor Servis</span>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $service->service_number }}</h2>
                        </div>
                        <div class="text-right">
                             <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Tanggal</span>
                             <p class="font-medium text-gray-800">{{ $service->service_date->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Steps/Progress (Simulated based on status) -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4 text-sm font-medium">
                            <div class="flex-1 pb-4 border-b-2 border-orange-500 text-orange-600">
                                01. Terdaftar
                            </div>
                            <div class="flex-1 pb-4 border-b-2 {{ $service->details->count() > 0 ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-300' }}">
                                02. Pengerjaan
                            </div>
                            <div class="flex-1 pb-4 border-b-2 {{ $service->status == 'lunas' ? 'border-green-500 text-green-600' : 'border-gray-100 text-gray-300' }}">
                                03. Selesai
                            </div>
                        </div>
                    </div>

                    <!-- Customer & Vehicle Info -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8 grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Pelanggan</p>
                            <p class="font-bold text-gray-800">{{ $service->vehicle->customer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $service->vehicle->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kendaraan</p>
                            <p class="font-bold text-gray-800">{{ $service->vehicle->name }}</p>
                            <p class="text-xs text-gray-500">{{ $service->vehicle->license_plate }}</p>
                        </div>
                    </div>

                    <!-- Complaint -->
                    @if($service->complaint)
                    <div class="mb-8">
                         <h3 class="font-bold text-gray-900 mb-2">Keluhan / Catatan</h3>
                         <p class="text-gray-600 text-sm leading-relaxed bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                            "{{ $service->complaint }}"
                         </p>
                    </div>
                    @endif
                    
                    @if($service->mechanic)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center text-white">
                            <i class="bi bi-wrench-adjustable"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Dikerjakan Oleh</p>
                            <p class="font-bold text-gray-900">{{ $service->mechanic->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Summary (Receipt) -->
                <div class="bg-gray-50 p-8 flex flex-col h-full">
                    <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-receipt text-orange-500"></i> Rincian Biaya
                    </h3>

                    <div class="flex-1 space-y-4">
                        <!-- Spareparts List -->
                         @foreach($service->details as $detail)
                         <div class="flex justify-between text-sm group">
                             <div class="flex gap-2">
                                <span class="bg-white px-1.5 rounded text-gray-500 text-xs border border-gray-200">x{{ $detail->quantity }}</span>
                                <span class="text-gray-600">{{ $detail->sparepart->name }}</span>
                             </div>
                             <span class="font-medium text-gray-900">{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                         </div>
                         @endforeach
                         
                         <!-- Divider -->
                         <div class="border-t border-dashed border-gray-300 my-4"></div>
                         
                         <div class="flex justify-between text-sm">
                             <span class="text-gray-500">Jasa Servis</span>
                             <span class="font-medium text-gray-900">{{ number_format($service->labor_cost, 0, ',', '.') }}</span>
                         </div>
                    </div>

                    <div class="mt-8 pt-4 border-t-2 border-gray-200">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm font-bold text-gray-600">Total Biaya</span>
                            <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($service->total, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="mt-4">
                            @if($service->status == 'lunas')
                            <div class="w-full py-3 bg-green-100 text-green-700 rounded-xl font-bold flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> LUNAS
                            </div>
                            @else
                            <div class="w-full py-3 bg-orange-100 text-orange-700 rounded-xl font-bold flex items-center justify-center gap-2">
                                <i class="bi bi-clock-fill"></i> BELUM LUNAS
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-3xl p-12 shadow-xl text-center animate-up max-w-lg mx-auto">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <i class="bi bi-emoji-frown text-3xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Data Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-8">Maaf, kami tidak menemukan data servis dengan nomor tersebut. Silakan periksa kembali.</p>
            <a href="{{ route('home.cek-service') }}" class="text-gray-900 font-semibold hover:text-orange-600 transition">Coda lagi nah</a>
        </div>
        @endif
    @endif
</section>

<style>
    @keyframes up {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-up {
        animation: up 0.5s ease-out forwards;
    }
</style>
@endsection
