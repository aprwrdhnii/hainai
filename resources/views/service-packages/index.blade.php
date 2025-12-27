@extends('layouts.app')
@section('title', 'Paket Servis')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Paket Servis</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola paket layanan bundling</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('service-packages.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Buat Paket
        </a>
    </div>
</div>

<!-- Packages Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($packages as $package)
    <div class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-900/50 hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
        
        <div class="mb-4">
            <h3 class="font-bold text-xl text-gray-800 dark:text-white group-hover:text-orange-600 transition">{{ $package->name }}</h3>
            <div class="text-2xl font-black text-orange-600 mt-2">
                Rp {{ number_format($package->price, 0, ',', '.') }}
            </div>
            @if($package->discount > 0)
            <div class="text-xs text-gray-400 line-through">
                Rp {{ number_format($package->price + $package->discount, 0, ',', '.') }}
            </div>
            @endif
        </div>
        
        <div class="flex-1">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $package->description }}</p>
            
            @if($package->features)
            <ul class="space-y-2 mb-6">
                @foreach(explode(',', $package->features) as $feature)
                <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <i class="bi bi-check-circle-fill text-green-500 mt-0.5 text-xs"></i>
                    {{ trim($feature) }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="pt-4 mt-4 border-t border-gray-50 dark:border-gray-700/50 flex gap-2">
            <a href="{{ route('service-packages.edit', $package) }}" class="flex-1 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold text-sm hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 transition text-center">
                Edit
            </a>
            <form action="{{ route('service-packages.destroy', $package) }}" method="POST" class="flex-none" onsubmit="return confirm('Hapus paket ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 transition flex items-center justify-center">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <i class="bi bi-box2-heart text-4xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada paket servis</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Buat paket menarik untuk meningkatkan penjualan.</p>
        <a href="{{ route('service-packages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
            <i class="bi bi-plus-lg"></i> Buat Paket
        </a>
    </div>
    @endforelse
</div>

@if($packages->hasPages())
<div class="mt-6">
    {{ $packages->links() }}
</div>
@endif
@endsection
