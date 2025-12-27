@extends('layouts.app')
@section('title', 'Daftar Pengeluaran')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Catatan Pengeluaran</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola operasional dan biaya lainnya</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('expenses.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> Catat Pengeluaran
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center group hover:border-orange-200 dark:hover:border-orange-900/50 transition">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Pengeluaran Hari Ini</p>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white">Rp {{ number_format($totalToday, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
            <i class="bi bi-calendar-event text-xl"></i>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center group hover:border-orange-200 dark:hover:border-orange-900/50 transition">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Bulan Ini</p>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 group-hover:scale-110 transition">
            <i class="bi bi-calendar-month text-xl"></i>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
    <form action="{{ route('expenses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-4 relative">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-3">
            <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-2">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-1 flex gap-2">
            <button type="submit" class="w-full btn bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-sm font-bold transition flex items-center justify-center">
                <i class="bi bi-search"></i>
            </button>
            @if(request()->hasAny(['search', 'category', 'start_date', 'end_date']))
            <a href="{{ route('expenses.index') }}" class="w-full btn bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Expense List -->
<div class="grid gap-3">
    @forelse($expenses as $expense)
    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-orange-200 dark:hover:border-orange-900/50 transition-all duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold text-lg shadow-inner flex-shrink-0">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white group-hover:text-orange-600 transition">{{ $expense->description }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <div class="flex items-center gap-1.5">
                            <i class="bi bi-calendar"></i> {{ $expense->expense_date->format('d M Y') }}
                        </div>
                        @if($expense->category)
                        <span class="text-gray-300">|</span>
                        <div class="flex items-center gap-1.5 font-medium text-orange-600 dark:text-orange-400">
                            {{ $expense->category }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-0 border-gray-50 dark:border-gray-700/50">
                <div class="text-right">
                    <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider">Nominal</span>
                    <span class="font-bold text-lg text-red-600 dark:text-red-400">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('expenses.edit', $expense) }}" class="w-9 h-9 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/30 hover:text-orange-600 transition flex items-center justify-center">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus catatan pengeluaran ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 transition flex items-center justify-center">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-wallet2 text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada catatan</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Mulai catat pengeluaran operasional Anda.</p>
        <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
            <i class="bi bi-plus-lg"></i> Catat Pengeluaran
        </a>
    </div>
    @endforelse
</div>

@if($expenses->hasPages())
<div class="mt-6">
    {{ $expenses->links() }}
</div>
@endif
@endsection
