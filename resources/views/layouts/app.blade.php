<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false, userMenu: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'HaiNai') - Sistem Bengkel</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('logo.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        orange: {
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .sidebar-active {
            background: linear-gradient(90deg, #fff7ed 0%, #ffffff 100%);
            border-right: 3px solid #f97316;
            color: #ea580c;
        }
        .dark .sidebar-active {
            background: linear-gradient(90deg, rgba(234, 88, 12, 0.1) 0%, transparent 100%);
            border-right: 3px solid #f97316;
            color: #fb923c;
        }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .dark .glass-header {
            background: rgba(17, 24, 39, 0.8);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Cool Premium Full Screen Loader */
        #pageLoader {
            transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
            opacity: 1;
            visibility: visible;
        }
        #pageLoader.hide {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 font-sans antialiased">
    
    <!-- Premium Glass Loader -->
    <div id="pageLoader" class="fixed inset-0 z-[100] bg-white/70 dark:bg-gray-900/80 backdrop-blur-md flex flex-col items-center justify-center">
        <div class="relative flex items-center justify-center mb-6">
            <!-- Pulsing Outer Ring -->
            <div class="absolute w-24 h-24 border-4 border-orange-200 dark:border-orange-900/40 rounded-full animate-ping opacity-75"></div>
            
            <!-- Spinning Gradient Ring -->
            <div class="absolute w-24 h-24 border-4 border-t-orange-600 border-r-orange-400 border-b-orange-200 border-l-transparent rounded-full animate-spin"></div>
            
            <!-- Logo in Center -->
            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-xl flex items-center justify-center relative z-10">
                <img src="{{ asset('logo.jpg') }}" class="w-12 h-12 rounded-full object-cover animate-pulse" alt="Loading">
            </div>
        </div>
        
        <div class="space-y-2 text-center">
            <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-orange-400 tracknig-tight">HaiNai</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 tracking-widest uppercase">Memuat Halaman...</p>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
            class="fixed lg:sticky lg:translate-x-0 top-0 left-0 w-72 h-screen z-50 transition-transform duration-300 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 flex flex-col">
            
            <!-- Logo area -->
            <div class="p-6 flex items-center gap-3">
                <img src="{{ asset('logo.jpg') }}" alt="HaiNai" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                <div>
                    <h1 class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">Hai<span class="text-orange-600">Nai</span></h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Admin Panel</p>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-gray-400">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto no-scrollbar py-4 space-y-1 px-4">
                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-grid-fill text-lg"></i>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('services.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-wrench-adjustable-circle-fill text-lg"></i>
                    <span>Servis & Perbaikan</span>
                </a>

                <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('invoices.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-receipt text-lg"></i>
                    <span>Nota & Tagihan</span>
                </a>

                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Database</p>

                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('customers.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-people-fill text-lg"></i>
                    <span>Pelanggan</span>
                </a>

                <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('vehicles.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-bicycle text-lg"></i>
                    <span>Kendaraan</span>
                </a>

                <a href="{{ route('spareparts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('spareparts.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-box-seam-fill text-lg"></i>
                    <span>Stok Onderdil</span>
                </a>
                
                <a href="{{ route('mechanics.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('mechanics.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-person-gear text-lg"></i>
                    <span>Montir</span>
                </a>

                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Keuangan & Laporan</p>

                <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('expenses.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-wallet-fill text-lg"></i>
                    <span>Pengeluaran</span>
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('reports.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-bar-chart-fill text-lg"></i>
                    <span>Laporan & Grafik</span>
                </a>

                <a href="{{ route('admin.whatsapp.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.whatsapp.*') ? 'bg-green-50 text-green-600 dark:bg-green-900/10 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="bi bi-whatsapp text-lg"></i>
                    <span>WhatsApp Service</span>
                </a>
                
                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">CMS Halaman Depan</p>
                <a href="{{ route('promos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('promos.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                     <i class="bi bi-tag-fill text-lg"></i>
                     <span>Promo</span>
                </a>
                <a href="{{ route('service-packages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('service-packages.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                     <i class="bi bi-box-fill text-lg"></i>
                     <span>Paket Servis</span>
                </a>
                 <a href="{{ route('testimonials.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('testimonials.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/10 dark:text-orange-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                     <i class="bi bi-chat-quote-fill text-lg"></i>
                     <span>Testimoni</span>
                </a>
            </nav>

            <!-- Footer User -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-semibold hover:bg-gray-100 dark:hover:bg-gray-600">
                        <i class="bi bi-gear-fill"></i> Setting
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-900/30">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
            <!-- Top Header -->
            <header class="glass-header sticky top-0 z-30 h-16 px-6 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">@yield('title', 'Beranda')</h2>
                </div>

                <div class="flex items-center gap-4">
                     <!-- View Home Button -->
                     <a href="{{ route('home') }}" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-orange-600 transition">
                         <i class="bi bi-box-arrow-up-right"></i> Lihat Website
                     </a>
                     
                     <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                        class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="bi text-lg" :class="darkMode ? 'bi-sun-fill text-yellow-500' : 'bi-moon-fill'"></i>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 scroll-smooth">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center gap-3 animate-fade-in-down">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-check-lg text-green-600 dark:text-green-400"></i>
                    </div>
                    <p class="flex-1 font-medium text-green-800 dark:text-green-200 text-sm">{{ session('success') }}</p>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 dark:hover:text-green-200"><i class="bi bi-x-lg"></i></button>
                </div>
                @endif
    
                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-3 animate-fade-in-down">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-exclamation-lg text-red-600 dark:text-red-400"></i>
                    </div>
                    <p class="flex-1 font-medium text-red-800 dark:text-red-200 text-sm">{{ session('error') }}</p>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 dark:hover:text-red-200"><i class="bi bi-x-lg"></i></button>
                </div>
                @endif

                @yield('content')
                
                <footer class="mt-12 text-center text-xs text-gray-400 pb-4">
                    &copy; {{ date('Y') }} HaiNai Sistem Bengkel. v{{ config('changelog.version', '1.0.0') }}
                </footer>
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        const loader = document.getElementById('pageLoader');
        
        // Hide loader when page is loaded
        window.addEventListener('load', () => loader.classList.add('hide'));
        window.addEventListener('pageshow', () => loader.classList.add('hide'));
        
        // Instant Reaction on Click
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.startsWith('#') && 
                !link.href.includes('javascript') &&
                link.target !== '_blank' &&
                link.hostname === window.location.hostname
            ) {
                if (!e.ctrlKey && !e.metaKey && !e.shiftKey && !e.altKey) {
                    loader.classList.remove('hide');
                }
            }
        });

        // Form handling
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                if(!form.target || form.target === '_self') loader.classList.remove('hide');
            });
        });
    </script>
</body>
</html>
