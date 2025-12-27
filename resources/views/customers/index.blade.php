@extends('layouts.app')
@section('title', 'Daftar Langganan')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Data Langganan</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Total {{ $customers->total() }} pelanggan terdaftar</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('customers.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Pelanggan
        </a>
    </div>
</div>

<!-- Customer List -->
<div class="grid gap-4">
    @forelse($customers as $customer)
    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-orange-200 dark:hover:border-orange-900/50 transition-all duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Info -->
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/40 dark:to-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold text-lg shadow-inner">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white group-hover:text-orange-600 transition">{{ $customer->name }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        @if($customer->phone)
                        <a href="https://wa.me/{{ $customer->phone }}" target="_blank" class="flex items-center gap-1.5 hover:text-green-500 transition" title="Chat WhatsApp">
                            <i class="bi bi-whatsapp"></i> {{ $customer->phone }}
                        </a>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        @endif
                        <div class="flex items-center gap-1.5" title="Jumlah Kendaraan">
                            <i class="bi bi-car-front-fill"></i> {{ $customer->vehicles_count }} Kendaraan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 pt-4 sm:pt-0 border-t sm:border-0 border-gray-50 dark:border-gray-700/50 w-full sm:w-auto">
                <a href="{{ route('customers.show', $customer) }}" class="flex-1 sm:flex-none py-2 px-4 rounded-xl text-sm font-medium bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition text-center">
                    Detail
                </a>
                <a href="{{ route('customers.edit', $customer) }}" class="flex-1 sm:flex-none py-2 px-4 rounded-xl text-sm font-medium bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-900/40 transition text-center">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="flex-none" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Data kendaraan dan riwayat servis juga akan terhapus.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-9 rounded-xl text-sm font-medium bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 transition flex items-center justify-center" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-people text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada pelanggan</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Tambahkan data pelanggan pertama Anda.</p>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
            <i class="bi bi-plus-lg"></i> Tambah Pelanggan
        </a>
    </div>
    @endforelse
</div>

@if($customers->hasPages())
<div class="mt-6">
    {{ $customers->links() }}
</div>
@endif
@endsection
