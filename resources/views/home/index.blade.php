@extends('layouts.public')
@section('title', 'Layanan Servis Modern')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[600px] flex items-center pt-24 pb-16 overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gray-900">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://images.unsplash.com/photo-1486262715619-01b80250e0dc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-transparent"></div>
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-orange-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-orange-400 text-sm font-medium mb-6">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    Buka: 08.00 - 17.00 WITA
                </div>
                <h1 class="text-4xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Solusi Servis Motor <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Pian Banar.</span>
                </h1>
                <p class="text-gray-300 text-lg mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Layanan bengkel modern dengan transparansi penuh. Pantau status servis pian secara real-time, estimasi biaya di awal, dan garansi kepuasan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('home.cek-service') }}" class="group bg-orange-500 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg hover:shadow-orange-500/25 flex items-center justify-center gap-2">
                        <i class="bi bi-search"></i> Cek Status Servis
                    </a>
                    <a href="https://wa.me/{{ config('services.whatsapp.boss_phone') }}" target="_blank" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 transition flex items-center justify-center gap-2">
                        <i class="bi bi-whatsapp"></i> Hubungi Kami
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="mt-12 flex items-center justify-center lg:justify-start gap-8 border-t border-white/10 pt-8">
                    <div>
                        <p class="text-3xl font-bold text-white">500+</p>
                        <p class="text-sm text-gray-400">Servis Bulan Ini</p>
                    </div>
                    <div class="w-px h-12 bg-white/10"></div>
                    <div>
                        <p class="text-3xl font-bold text-white">4.9/5</p>
                        <p class="text-sm text-gray-400">Rating Pelanggan</p>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 relative hidden lg:block">
               <div class="relative z-10 bg-gray-800/50 backdrop-blur-xl border border-white/10 p-2 rounded-3xl shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                    <img src="https://images.unsplash.com/photo-1578844251758-2f71da645217?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Mechanic" class="rounded-2xl w-full">
                    
                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 animate-bounce" style="animation-duration: 3s">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Status Servis</p>
                            <p class="font-bold text-gray-800">Selesai Dikerjakan</p>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
</section>

<!-- Bento Grid Menu -->
<section class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Feature -->
        <div class="md:col-span-1 bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition border border-gray-100 group">
            <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 text-2xl mb-6 group-hover:scale-110 transition">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Sparepart Original</h3>
            <p class="text-gray-500 mb-6">Cek ketersediaan onderdil asli dengan harga transparan langsung dari sistem kami.</p>
            <a href="{{ route('home.spareparts') }}" class="inline-flex items-center gap-2 text-orange-600 font-semibold group-hover:gap-3 transition">
                Lihat Katalog <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Secondary Features -->
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <a href="{{ route('home.estimasi') }}" class="bg-gray-900 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-125">
                    <i class="bi bi-calculator text-9xl text-white"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-white text-xl mb-6">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">Estimasi Biaya</h3>
                    <p class="text-gray-400 text-sm">Hitung perkiraan biaya servis</p>
                </div>
            </a>

            <a href="{{ route('home.cek-service') }}" class="bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition group text-white">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl">
                        <i class="bi bi-search"></i>
                    </div>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium">Populer</span>
                </div>
                <h3 class="text-xl font-bold mb-1">Lacak Servis</h3>
                <p class="text-orange-100 text-sm">Pantau motor pian</p>
            </a>
        </div>
    </div>
</section>

<!-- Promo Section -->
@if($promos->count() > 0)
<section class="max-w-7xl mx-auto px-4 mb-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Promo Spesial</h2>
            <p class="text-gray-500 mt-2">Dapatkan penawaran terbaik bulan ini</p>
        </div>
        <div class="hidden md:flex gap-2">
            <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50"><i class="bi bi-chevron-left"></i></button>
            <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <div class="flex overflow-x-auto pb-8 gap-6 snap-x">
        @foreach($promos as $promo)
        <div class="min-w-[300px] md:min-w-[350px] bg-white rounded-3xl p-6 border border-gray-100 shadow-lg snap-center hover:shadow-xl transition">
            <div class="bg-orange-50 rounded-2xl p-4 mb-4">
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">PROMO</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $promo->title }}</h3>
            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $promo->description }}</p>
            @if($promo->end_date)
            <div class="flex items-center gap-2 text-sm text-gray-400 border-t border-gray-100 pt-4">
                <i class="bi bi-clock"></i>
                <span>Berlaku s/d {{ $promo->end_date->format('d M Y') }}</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Packages Section -->
@if($packages->count() > 0)
<section class="bg-gray-900 py-20 rounded-[3rem] my-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-orange-500 font-semibold tracking-wider text-sm">PAKET SERVIS</span>
            <h2 class="text-3xl md:text-5xl font-bold text-white mt-2 mb-4">Pilihan Paket Hemat</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Pilih paket servis yang sesuai dengan kebutuhan motor pian. Harga transparan tanpa biaya tersembunyi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $package)
            <div class="bg-gray-800 rounded-3xl p-8 border border-gray-700 hover:border-orange-500 transition group relative overflow-hidden">
                @if($loop->iteration == 2)
                <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-4 py-1 rounded-bl-xl">POPULER</div>
                @endif
                
                <h3 class="text-2xl font-bold text-white mb-2">{{ $package->name }}</h3>
                <p class="text-gray-400 text-sm mb-8 h-10">{{ $package->description }}</p>
                
                <div class="flex items-baseline gap-1 mb-8">
                    <span class="text-gray-400 text-sm">Mulai</span>
                    <span class="text-4xl font-bold text-white">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3 text-gray-300">
                        <i class="bi bi-check-circle-fill text-green-500"></i>
                        <span>Pengecekan Standar</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-300">
                        <i class="bi bi-check-circle-fill text-green-500"></i>
                        <span>Garansi Servis</span>
                    </li>
                    @if($package->duration)
                    <li class="flex items-center gap-3 text-gray-300">
                        <i class="bi bi-clock-fill text-blue-500"></i>
                        <span>Est. {{ $package->duration }}</span>
                    </li>
                    @endif
                </ul>

                <a href="https://wa.me/{{ config('services.whatsapp.boss_phone') }}?text=Halo, ulun handak booking {{ $package->name }}" class="block w-full text-center py-4 rounded-xl font-bold transition {{ $loop->iteration == 2 ? 'bg-orange-500 text-white hover:bg-orange-600' : 'bg-gray-700 text-white hover:bg-gray-600' }}">
                    Booking Sekarang
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials -->
<section class="max-w-7xl mx-auto px-4 mb-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 uppercase leading-none">Apa Kata <br><span class="text-orange-500">Buhan Sidin.</span></h2>
            <p class="text-gray-500 text-lg mb-8">Ribuan pelanggan puas dengan layanan kami. Jadilah bagian dari mereka yang menikmati servis berkualitas.</p>
            
            <div class="flex gap-4">
                <button onclick="document.getElementById('testimonialModal').classList.remove('hidden')" class="px-8 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition">
                    Tulis Pengalaman
                </button>
                <div class="flex items-center -space-x-3">
                    @foreach($testimonials->take(3) as $t)
                    <div class="w-10 h-10 rounded-full bg-orange-100 border-2 border-white flex items-center justify-center text-xs font-bold text-orange-600">
                        {{ substr($t->name, 0, 1) }}
                    </div>
                    @endforeach
                    <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs text-gray-500">
                        +{{ $testimonials->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($testimonials->take(4) as $testimonial)
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:-translate-y-1 transition duration-300">
                <div class="flex text-yellow-400 text-sm mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">"{{ $testimonial->content }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr($testimonial->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm text-gray-900">{{ $testimonial->name }}</p>
                        <p class="text-xs text-gray-400">{{ $testimonial->vehicle ?? 'Pelanggan' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonial Modal (Reused but styled) -->
<div id="testimonialModal" class="hidden fixed inset-0 bg-black/60 z-[60] backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl relative animate-up">
        <button onclick="document.getElementById('testimonialModal').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <h3 class="font-bold text-2xl mb-2">Bagikan Pengalaman</h3>
        <p class="text-gray-500 text-sm mb-6">Bantu kami meningkatkan kualitas layanan dengan masukan pian.</p>
        
        <form action="{{ route('home.testimonial.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nama Pian</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-0 transition">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Motor (Opsional)</label>
                <input type="text" name="vehicle" placeholder="cth: Honda Beat 2020" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-0 transition">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Rating</label>
                <div class="flex gap-2">
                    @for($i = 5; $i >= 1; $i--)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" {{ $i==5 ? 'checked' : '' }}>
                        <span class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 text-sm transition">{{ $i }} <i class="bi bi-star-fill text-[10px]"></i></span>
                    </label>
                    @endfor
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Ulasan</label>
                <textarea name="content" rows="3" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-0 transition" placeholder="Tuliskan pengalaman pian..."></textarea>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-orange-500/30 transition transform active:scale-95">
                Kirim Testimoni
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 z-50 animate-bounce">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

<style>
    @keyframes up {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-up {
        animation: up 0.3s ease-out forwards;
    }
</style>
@endsection
