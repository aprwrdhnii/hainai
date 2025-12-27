@extends('layouts.app')
@section('title', 'Buat Invoice Manual')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Invoice Manual</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Catat transaksi penjualan langsung / non-servis</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="invoiceForm()">
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex justify-between items-center">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="bg-transparent border-none p-0 font-bold text-gray-800 dark:text-white focus:ring-0 cursor-pointer">
                </div>
                <div class="text-right">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Tagihan</label>
                    <div class="text-2xl font-black text-orange-600">Rp <span x-text="formatNumber(grandTotal)">0</span></div>
                </div>
            </div>

            <div class="p-6 space-y-8">
                <!-- Section 1: Customer -->
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">1</span>
                        Informasi Pelanggan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pelanggan</label>
                            <select name="customer_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="">- Pelanggan Umum -</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Tambahan</label>
                            <input type="text" name="notes" placeholder="Keterangan transaksi..." class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Items -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                             <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">2</span>
                             Daftar Barang
                        </h3>
                        <button type="button" @click="addItem()" class="text-orange-600 hover:text-orange-700 font-bold text-sm flex items-center gap-1">
                            <i class="bi bi-plus-circle"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex gap-3 items-start p-3 bg-gray-50 dark:bg-gray-900/20 rounded-xl border border-gray-100 dark:border-gray-700">
                                <div class="flex-1">
                                    <select :name="'items['+index+'][sparepart_id]'" x-model="item.sparepart_id" @change="updatePrice(index, $event.target.value)" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm" required>
                                        <option value="">Pilih Barang...</option>
                                        @foreach($spareparts as $part)
                                        <option value="{{ $part->id }}" data-price="{{ $part->sell_price }}">
                                            {{ $part->name }} (Stok: {{ $part->stock }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="1" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm text-center" placeholder="Qty" required>
                                </div>
                                <div class="w-32 pt-2.5 text-right font-medium text-gray-700 dark:text-gray-300">
                                    Rp <span x-text="formatNumber(item.price * item.qty)">0</span>
                                </div>
                                <button type="button" @click="removeItem(index)" class="pt-2.5 text-red-500 hover:text-red-700">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="px-8 py-3 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold shadow-lg shadow-orange-600/20 transition flex items-center gap-2">
                        <i class="bi bi-save"></i> Simpan Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function invoiceForm() {
        return {
            items: [{ sparepart_id: '', qty: 1, price: 0 }],
            addItem() {
                this.items.push({ sparepart_id: '', qty: 1, price: 0 });
            },
            removeItem(index) {
                if(this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            updatePrice(index, partId) {
                const select = document.querySelector(`select[name="items[${index}][sparepart_id]"]`);
                const option = select.options[select.selectedIndex];
                this.items[index].price = option.dataset.price ? parseInt(option.dataset.price) : 0;
            },
            get grandTotal() {
                return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }
    }
</script>
@endpush
@endsection
