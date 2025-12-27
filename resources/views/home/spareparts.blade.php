@extends('layouts.public')
@section('title', 'Katalog Onderdil')

@section('content')
<!-- Header Section -->
<section class="bg-gray-900 pt-32 pb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Katalog Onderdil</h1>
        <p class="text-gray-400 max-w-2xl mx-auto text-lg">Cari berbagai macam onderdil berkualitas gasan motor pian. Harga transparan, stok update.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-10 relative z-20 pb-20">
    <!-- Search & Filter Card -->
    <div class="bg-white rounded-3xl p-6 shadow-xl mb-12 border border-gray-100">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-orange-500 transition"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari onderdil apa hari ini?" 
                    class="w-full pl-11 pr-4 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition">
            </div>
            <div class="md:w-64 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-filter text-gray-400"></i>
                </div>
                <select name="category" class="w-full pl-11 pr-10 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                </div>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-2xl font-bold transition shadow-lg shadow-orange-500/20 active:scale-95">
                Cari Barang
            </button>
        </form>
    </div>
    
    <!-- Sparepart Grid -->
    @if($spareparts->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($spareparts as $sparepart)
        <div class="group bg-white rounded-3xl p-4 shadow-lg hover:shadow-2xl transition duration-300 border border-transparent hover:border-orange-100">
            <div class="aspect-square bg-gray-50 rounded-2xl flex items-center justify-center mb-4 overflow-hidden relative group-hover:bg-orange-50 transition">
                @if($sparepart->image)
                <img src="{{ asset('uploads/spareparts/' . $sparepart->image) }}" alt="{{ $sparepart->name }}" 
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                @else
                <i class="bi bi-gear text-5xl text-gray-300 group-hover:text-orange-400 transition transform group-hover:rotate-45"></i>
                @endif
                
                @if($sparepart->stock > 0)
                <div class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">
                    READY
                </div>
                @else
                <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">
                    HABIS
                </div>
                @endif
            </div>
            
            <div class="px-2 pb-2">
                @if($sparepart->category)
                <p class="text-xs text-orange-500 font-medium mb-1 uppercase tracking-wide">{{ $sparepart->category }}</p>
                @endif
                <h3 class="font-bold text-gray-800 text-lg mb-2 leading-tight group-hover:text-orange-600 transition truncate">{{ $sparepart->name }}</h3>
                <div class="flex justify-between items-end border-t border-gray-50 pt-3 mt-2">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Harga</p>
                        <p class="text-gray-900 font-bold">Rp {{ number_format($sparepart->sell_price, 0, ',', '.') }}</p>
                    </div>
                    <button class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-orange-500 hover:text-white transition">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-12">
        {{ $spareparts->withQueryString()->links() }}
    </div>
    @else
    <div class="bg-white rounded-3xl p-16 text-center border-2 border-dashed border-gray-200">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-search text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Barang kada tatamu</h3>
        <p class="text-gray-500 max-w-sm mx-auto">Cuba cari lawan kata kunci lain atau hubungi admin lewat WhatsApp.</p>
        <a href="https://wa.me/{{ config('services.whatsapp.boss_phone') }}" class="inline-flex items-center gap-2 mt-6 text-orange-600 font-semibold hover:underline">
            <i class="bi bi-whatsapp"></i> Takuni Admin
        </a>
    </div>
    @endif
</div>
@endsection
