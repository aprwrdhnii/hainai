<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HaiNai') - Bengkel Motor Taparcaya</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('logo.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background: linear-gradient(to right, #f97316, #ea580c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased selection:bg-orange-500 selection:text-white pb-20 md:pb-0">
    <!-- Navbar -->
    <nav class="glass-nav fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative">
                        <img src="{{ asset('logo.jpg') }}" alt="HaiNai" class="w-10 h-10 rounded-xl object-cover shadow-md group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-black/10"></div>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-gray-900">Hai<span class="text-gradient">Nai</span></span>
                </a>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition">Rumah</a>
                    <a href="{{ route('home.spareparts') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition">Onderdil</a>
                    <a href="{{ route('home.estimasi') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition">Estimasi</a>
                    <a href="{{ route('home.cek-service') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition">Cek Servis</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home.cek-service') }}" class="hidden md:inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-gray-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="bi bi-search"></i> Lacak Status
                    </a>
                    <a href="{{ route('dashboard') }}" class="p-2 text-gray-400 hover:text-gray-600 transition rounded-full hover:bg-gray-100">
                        <i class="bi bi-person-circle text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Mobile Bottom Nav -->
        <div class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 pb-safe">
            <div class="grid grid-cols-4 gap-1 p-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center py-2 px-1 rounded-lg {{ request()->routeIs('home') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Rumah</span>
                </a>
                 <a href="{{ route('home.spareparts') }}" class="flex flex-col items-center py-2 px-1 rounded-lg {{ request()->routeIs('home.spareparts') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                    <i class="bi bi-gear{{ request()->routeIs('home.spareparts') ? '-fill' : '' }} text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Onderdil</span>
                </a>
                 <a href="{{ route('home.estimasi') }}" class="flex flex-col items-center py-2 px-1 rounded-lg {{ request()->routeIs('home.estimasi') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                    <i class="bi bi-calculator{{ request()->routeIs('home.estimasi') ? '-fill' : '' }} text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Estimasi</span>
                </a>
                 <a href="{{ route('home.cek-service') }}" class="flex flex-col items-center py-2 px-1 rounded-lg {{ request()->routeIs('home.cek-service') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                    <i class="bi bi-search{{ request()->routeIs('home.cek-service') ? '' : '' }} text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Lacak</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="md:pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white rounded-t-[3rem] mt-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 pt-16 pb-24 md:pb-10 relative">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('logo.jpg') }}" alt="HaiNai" class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/20">
                        <span class="font-bold text-2xl tracking-tight">HaiNai</span>
                    </div>
                    <p class="text-gray-400 text-lg leading-relaxed max-w-sm mb-8">Bengkel modern dengan pelayanan "Pian Banar". Transparan, cepat, dan terpercaya untuk semua jenis motor anda.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition text-white hover:scale-110"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition text-white hover:scale-110"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition text-white hover:scale-110"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-6 text-orange-500">Menu Pintas</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="{{ route('home.spareparts') }}" class="hover:text-white transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Katalog Onderdil</a></li>
                        <li><a href="{{ route('home.estimasi') }}" class="hover:text-white transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Cek Biaya Servis</a></li>
                        <li><a href="{{ route('home.cek-service') }}" class="hover:text-white transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Tracking Status</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Login Admin</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-6 text-orange-500">Kontak & Lokasi</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-whatsapp text-green-500 text-xl mt-1"></i>
                            <span>{{ config('services.whatsapp.boss_phone', '08xx-xxxx-xxxx') }}</span>
                        </li>
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-geo-alt text-red-500 text-xl mt-1"></i>
                            <span>Dusun Banjarsari, Desa Karangrejo, Tran 200 (Depan Masjid Almuhajirin)</span>
                        </li>
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-clock text-blue-500 text-xl mt-1"></i>
                            <span>Buka Setiap Hari<br><span class="text-sm opacity-60">(Kecuali hari kiamat)</span></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} HaiNai Bengkel. All rights reserved.</p>
                <p>Dibuat dengan <i class="bi bi-heart-fill text-red-500 animate-pulse"></i> untuk Banua</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
