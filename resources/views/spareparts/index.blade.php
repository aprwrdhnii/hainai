@extends('layouts.app')
@section('title', 'Daftar Onderdil')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Stok Onderdil</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Total {{ $spareparts->total() }} jenis barang</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <button onclick="document.getElementById('importModal').showModal()" 
            class="flex-1 sm:flex-none btn bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-medium shadow-lg shadow-green-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> <span class="hidden sm:inline">Impor Excel/CSV</span>
        </button>
        <a href="{{ route('spareparts.create') }}" class="flex-1 sm:flex-none btn bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-lg shadow-orange-600/20 transition flex items-center justify-center gap-2">
            <i class="bi bi-plus-lg"></i> <span class="hidden sm:inline">Tambah Barang</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <!-- Sidebar Filters & Quick Add -->
    <div class="space-y-6">
        <!-- Quick Add -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-charge text-yellow-500"></i> Tambah Cepat
            </h3>
            <form action="{{ route('spareparts.quick-store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" required placeholder="Nama Onderdil"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <div class="flex gap-2">
                    <input type="number" name="sell_price" required min="0" placeholder="Harga Jual"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <input type="number" name="stock" min="0" value="0" placeholder="Stok"
                        class="w-24 px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                </div>
                <button type="submit" class="w-full btn bg-orange-600 hover:bg-orange-700 text-white py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                    Simpan
                </button>
            </form>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 sticky top-24">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Filter Pencarian</h3>
            <form action="{{ route('spareparts.index') }}" method="GET" class="space-y-3">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                        class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                </div>
                
                <select name="category" class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="stock" class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <option value="">Semua Status Stok</option>
                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Menipis/Habis</option>
                    <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Stok Tersedia</option>
                </select>

                <button type="submit" class="w-full btn bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-xl text-sm font-bold transition">
                    Terapkan Filter
                </button>
                
                @if(request()->has('search') || request()->has('category') || request()->has('stock'))
                <a href="{{ route('spareparts.index') }}" class="block w-full text-center py-2 text-sm text-gray-500 hover:text-orange-600 transition">
                    Reset Filter
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Main List -->
    <div class="lg:col-span-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            @forelse($spareparts as $sparepart)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border {{ $sparepart->stock <= $sparepart->min_stock ? 'border-red-500/50 ring-1 ring-red-500/20' : 'border-gray-100 dark:border-gray-700' }} hover:shadow-md hover:border-orange-200 dark:hover:border-orange-900/50 transition-all duration-300">
                <div class="flex justify-between items-start mb-3">
                    <div>
                         @if($sparepart->category)
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400">{{ $sparepart->category }}</span>
                        @endif
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white group-hover:text-orange-600 transition line-clamp-1" title="{{ $sparepart->name }}">
                            {{ $sparepart->name }}
                        </h3>
                        <p class="text-xs text-gray-500 font-mono mt-1">{{ $sparepart->code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 dark:text-white">Rp {{ number_format($sparepart->sell_price, 0, ',', '.') }}</p>
                        @if($sparepart->stock <= $sparepart->min_stock)
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 animate-pulse">
                            KRITIS: {{ $sparepart->stock }}
                        </span>
                        @else
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                            Stok: {{ $sparepart->stock }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-50 dark:border-gray-700/50">
                    <button onclick="openAddStockModal({{ $sparepart->id }}, '{{ $sparepart->name }}', {{ $sparepart->stock }})" 
                        class="flex-1 py-2 px-3 rounded-xl text-xs font-bold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 transition text-center flex items-center justify-center gap-1">
                        <i class="bi bi-plus-lg"></i> Stok
                    </button>
                    <a href="{{ route('spareparts.edit', $sparepart) }}" class="flex-1 py-2 px-3 rounded-xl text-xs font-bold bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition text-center flex items-center justify-center gap-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('spareparts.destroy', $sparepart) }}" method="POST" class="flex-none" onsubmit="return confirm('Hapus barang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-xl text-xs bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 transition flex items-center justify-center">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                <i class="bi bi-box-seam text-4xl text-gray-400 mb-3 block"></i>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada barang ditemukan.</p>
            </div>
            @endforelse
        </div>

        @if($spareparts->hasPages())
        <div class="mt-6">
            {{ $spareparts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Import Modal using Dialog -->
<dialog id="importModal" class="modal rounded-2xl shadow-2xl p-0 w-full max-w-md bg-white dark:bg-gray-800 backdrop:bg-gray-900/50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-800 dark:text-white">Impor Data CSV</h3>
            <form method="dialog">
                <button class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </form>
        </div>
        
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-sm flex items-start gap-3">
            <i class="bi bi-info-circle-fill text-blue-600 dark:text-blue-400 text-lg mt-0.5"></i>
            <div>
                <p class="text-blue-800 dark:text-blue-300 font-medium mb-1">Format Kolom CSV:</p>
                <p class="text-blue-600 dark:text-blue-400">Name, Sell Price, Stock, Category</p>
                <a href="{{ route('spareparts.template') }}" class="inline-block mt-2 text-blue-700 dark:text-blue-300 font-bold hover:underline">
                    <i class="bi bi-download"></i> Download Template
                </a>
            </div>
        </div>

        <form action="{{ route('spareparts.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <input type="file" name="file" accept=".csv,.txt" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>
            <button type="submit" class="w-full btn bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold transition">
                Mulai Impor
            </button>
        </form>
    </div>
</dialog>

<!-- Add Stock Modal -->
<dialog id="addStockModal" class="modal rounded-2xl shadow-2xl p-0 w-full max-w-sm bg-white dark:bg-gray-800 backdrop:bg-gray-900/50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Tambah Stok</h3>
            <form method="dialog">
                <button class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </form>
        </div>
        
        <form id="addStockForm" method="POST">
            @csrf
            <div class="text-center mb-6">
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Menambah stok untuk</p>
                <h4 class="font-bold text-xl text-gray-800 dark:text-white" id="modalSparepartName"></h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Sisa stok: <span id="modalCurrentStock" class="font-mono font-bold text-gray-800 dark:text-gray-200"></span></p>
            </div>
            
            <div class="flex items-center justify-center gap-4 mb-6">
                <button type="button" onclick="this.nextElementSibling.stepDown()" class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 text-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 aspect-square">-</button>
                <input type="number" name="quantity" min="1" value="1" required
                    class="w-24 h-12 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-center text-xl font-bold focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <button type="button" onclick="this.previousElementSibling.stepUp()" class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 text-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 aspect-square">+</button>
            </div>

            <button type="submit" class="w-full btn bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold shadow-lg shadow-green-600/20 transition">
                Konfirmasi
            </button>
        </form>
    </div>
</dialog>

@push('scripts')
<script>
function openAddStockModal(id, name, stock) {
    const modal = document.getElementById('addStockModal');
    document.getElementById('modalSparepartName').textContent = name;
    document.getElementById('modalCurrentStock').textContent = stock;
    document.getElementById('addStockForm').action = '/admin/spareparts/' + id + '/add-stock';
    modal.showModal();
}
</script>
@endpush
@endsection
