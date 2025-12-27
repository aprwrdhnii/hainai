@extends('layouts.app')
@section('title', 'Buat Servis Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Formulir Servis</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Isi data pelanggan dan keluhan kendaraan.</p>
        </div>
        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div x-data="serviceForm()">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
    
            <!-- STEPS CONTAINER -->
            <div class="space-y-6">
                
                <!-- STEP 1: PELANGGAN -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-bold text-sm">1</div>
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Data Pelanggan</h3>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-1 rounded-xl flex text-sm">
                                <button type="button" @click="customerType = 'existing'" 
                                    :class="customerType === 'existing' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                                    class="px-3 py-1.5 rounded-lg font-medium transition-all">Pelanggan Lama</button>
                                <button type="button" @click="customerType = 'new'"
                                    :class="customerType === 'new' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                                    class="px-3 py-1.5 rounded-lg font-medium transition-all">Baru</button>
                            </div>
                        </div>
                        <input type="hidden" name="customer_type" :value="customerType">
    
                        <div x-show="customerType === 'existing'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Pelanggan</label>
                            <select name="customer_id" x-model="selectedCustomerId" @change="loadVehicles()"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">Pilih Pelanggan...</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->phone ? '('.$customer->phone.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div x-show="customerType === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-4" x-transition>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pelanggan *</label>
                                <input type="text" name="customer_name" placeholder="John Doe"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. WhatsApp</label>
                                <input type="text" name="customer_phone" placeholder="08123456789"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- STEP 2: KENDARAAN -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center font-bold text-sm">2</div>
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Data Kendaraan</h3>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-1 rounded-xl flex text-sm">
                                <button type="button" @click="vehicleType = 'existing'" 
                                    :class="vehicleType === 'existing' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                                    class="px-3 py-1.5 rounded-lg font-medium transition-all">Motor Lama</button>
                                <button type="button" @click="vehicleType = 'new'"
                                    :class="vehicleType === 'new' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                                    class="px-3 py-1.5 rounded-lg font-medium transition-all">Motor Baru</button>
                            </div>
                        </div>
                        <input type="hidden" name="vehicle_type" :value="vehicleType">
    
                        <div x-show="vehicleType === 'existing'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Motor</label>
                            <select name="vehicle_id" x-model="selectedVehicleId"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="">Pilih Motor...</option>
                                <template x-if="customerType === 'existing' && customerVehicles.length > 0">
                                    <template x-for="v in customerVehicles" :key="v.id">
                                        <option :value="v.id" x-text="v.name"></option>
                                    </template>
                                </template>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" x-show="customerType === 'new' || customerVehicles.length === 0">{{ $vehicle->name }} - {{ $vehicle->customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div x-show="vehicleType === 'new'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama / Tipe Motor</label>
                            <input type="text" name="vehicle_name" placeholder="Honda Beat 2020 / DA 1234 ABC"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        </div>
                    </div>
                </div>
    
                <!-- STEP 3: DETAIL SERVIS -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center font-bold text-sm">3</div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Detail Pengerjaan</h3>
                        </div>
                        
                        <input type="hidden" name="service_date" value="{{ date('Y-m-d') }}">
                        <input type="hidden" name="service_time" value="{{ date('H:i') }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montir yang Mengerjakan</label>
                                <div class="relative">
                                    <select name="mechanic_id"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none">
                                        @foreach($mechanics as $mechanic)
                                        <option value="{{ $mechanic->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $mechanic->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keluhan / Catatan</label>
                                <textarea name="complaint" rows="3" placeholder="Contoh: Ganti oli + servis rutin, rem bunyi..."
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- STEP 4: ONDERDIL -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center font-bold text-sm">4</div>
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Penggunaan Onderdil</h3>
                            </div>
                            <button type="button" @click="addSparepart()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-lg shadow-green-600/20 flex items-center gap-2">
                                <i class="bi bi-plus-lg"></i> Tambah Item
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-if="selectedSpareparts.length === 0">
                                <div class="text-center py-8 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                                    <i class="bi bi-box-seam text-4xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada onderdil yang dipilih</p>
                                </div>
                            </template>
    
                            <template x-for="(item, index) in selectedSpareparts" :key="index">
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700/50 transition hover:border-green-200 dark:hover:border-green-900">
                                    <input type="hidden" :name="'spareparts['+index+'][id]'" :value="item.id">
                                    <input type="hidden" :name="'spareparts['+index+'][qty]'" :value="item.qty">
                                    
                                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                                        <!-- Search / Name -->
                                        <div class="flex-1 w-full relative">
                                            <div class="relative">
                                                <i class="bi bi-search absolute left-3 top-3.5 text-gray-400"></i>
                                                <input type="text" 
                                                    x-model="item.search"
                                                    @focus="item.showDropdown = true"
                                                    @input="item.showDropdown = true"
                                                    @click.away="item.showDropdown = false"
                                                    :placeholder="item.id ? '' : 'Ketik nama onderdil...'"
                                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                            </div>
                                            
                                            <!-- Selected display overlay -->
                                            <div x-show="item.id && !item.showDropdown" 
                                                @click="item.showDropdown = true; item.search = ''"
                                                class="absolute inset-0 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl flex items-center cursor-pointer border border-green-500/30">
                                                <span class="font-medium text-gray-800 dark:text-white truncate" x-text="item.name"></span>
                                            </div>
                                            
                                            <!-- Dropdown -->
                                            <div x-show="item.showDropdown" 
                                                class="absolute z-20 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                                <template x-for="sp in filteredSpareparts(item.search)" :key="sp.id">
                                                    <div @click="selectSparepart(index, sp)" 
                                                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-0 flex justify-between items-center group">
                                                        <div>
                                                            <div class="font-medium text-gray-800 dark:text-white group-hover:text-green-600 transition" x-text="sp.name"></div>
                                                            <div class="text-xs text-gray-500" x-text="'Stok: ' + sp.stock"></div>
                                                        </div>
                                                        <div class="font-bold text-gray-700 dark:text-gray-300" x-text="'Rp ' + numberFormat(sp.sell_price)"></div>
                                                    </div>
                                                </template>
                                                <div x-show="filteredSpareparts(item.search).length === 0" class="px-4 py-3 text-center text-gray-500 text-sm">
                                                    Tidak ditemukan
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Qty & Price -->
                                        <div class="flex items-center gap-4 w-full md:w-auto">
                                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 rounded-lg p-1 border border-gray-200 dark:border-gray-700">
                                                <button type="button" @click="item.qty > 1 ? item.qty-- : null; updateSubtotal(index)" class="w-8 h-8 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center transition">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <span class="w-8 text-center font-bold text-gray-800 dark:text-white" x-text="item.qty"></span>
                                                <button type="button" @click="item.qty++; updateSubtotal(index)" class="w-8 h-8 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center transition">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="w-32 text-right">
                                                <span class="block text-xs text-gray-500">Subtotal</span>
                                                <span class="font-bold text-gray-800 dark:text-white" x-text="'Rp ' + numberFormat(item.subtotal)"></span>
                                            </div>

                                            <button type="button" @click="removeSparepart(index)" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center justify-center transition ml-auto md:ml-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
    
                <!-- SUMMARY & PAYMENT -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                         <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Status Pembayaran</h3>
                            <div class="grid grid-cols-2 gap-4" x-data="{ status: 'lunas' }">
                                <input type="hidden" name="status" :value="status">
                                <button type="button" @click="status = 'bon'"
                                    :class="status === 'bon' ? 'ring-2 ring-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700'"
                                    class="relative p-4 rounded-xl text-left transition-all duration-200 group">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div :class="status === 'bon' ? 'bg-orange-100 text-orange-600' : 'bg-white dark:bg-gray-700 text-gray-400'" 
                                            class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                            <i class="bi bi-receipt text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold" :class="status === 'bon' ? 'text-orange-900 dark:text-orange-300' : 'text-gray-600 dark:text-gray-300'">BON / HUTANG</span>
                                            <span class="text-xs text-gray-500">Bayar nanti (masuk piutang)</span>
                                        </div>
                                    </div>
                                    <div x-show="status === 'bon'" class="absolute top-4 right-4 text-orange-500"><i class="bi bi-check-circle-fill"></i></div>
                                </button>
            
                                <button type="button" @click="status = 'lunas'"
                                    :class="status === 'lunas' ? 'ring-2 ring-green-500 bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700'"
                                    class="relative p-4 rounded-xl text-left transition-all duration-200 group">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div :class="status === 'lunas' ? 'bg-green-100 text-green-600' : 'bg-white dark:bg-gray-700 text-gray-400'" 
                                            class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                            <i class="bi bi-cash-stack text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold" :class="status === 'lunas' ? 'text-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-300'">LUNAS</span>
                                            <span class="text-xs text-gray-500">Bayar sekarang (masuk kas)</span>
                                        </div>
                                    </div>
                                    <div x-show="status === 'lunas'" class="absolute top-4 right-4 text-green-500"><i class="bi bi-check-circle-fill"></i></div>
                                </button>
                            </div>
                         </div>
                    </div>
                    
                    <div class="lg:col-span-1">
                        <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-xl sticky top-24">
                            <h3 class="font-bold text-lg mb-6 border-b border-gray-700 pb-4">Ringkasan Biaya</h3>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center text-gray-300">
                                    <span>Total Onderdil</span>
                                    <span class="font-medium" x-text="'Rp ' + numberFormat(totalSpareparts)"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Biaya Jasa</span>
                                    <div class="relative w-32">
                                        <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="labor_cost" x-model.number="laborCost" min="0" 
                                            class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-white text-right focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-700 pt-4 mb-6">
                                <div class="flex justify-between items-end">
                                    <span class="text-gray-400 text-sm">Total Tagihan</span>
                                    <span class="text-3xl font-bold text-orange-400" x-text="'Rp ' + numberFormat(grandTotal)"></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold shadow-lg shadow-blue-900/50 transition transform hover:scale-[1.02] active:scale-[0.98]">
                                Simpan Servis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
const sparepartsList = @json($spareparts);

function serviceForm() {
    return {
        customerType: 'new',
        vehicleType: 'new',
        selectedCustomerId: '',
        selectedVehicleId: '',
        customerVehicles: [],
        selectedSpareparts: [],
        laborCost: 0,

        get totalSpareparts() {
            return this.selectedSpareparts.reduce((sum, item) => sum + (item.subtotal || 0), 0);
        },

        get grandTotal() {
            return this.totalSpareparts + (this.laborCost || 0);
        },

        loadVehicles() {
            if (!this.selectedCustomerId) {
                this.customerVehicles = [];
                return;
            }
            fetch(`/api/customers/${this.selectedCustomerId}/vehicles`)
                .then(res => res.json())
                .then(data => {
                    this.customerVehicles = data;
                    this.selectedVehicleId = data.length > 0 ? data[0].id : '';
                });
        },

        addSparepart() {
            this.selectedSpareparts.push({ 
                id: '', 
                name: '',
                search: '',
                showDropdown: true,
                qty: 1, 
                price: 0, 
                subtotal: 0 
            });
        },

        removeSparepart(index) {
            this.selectedSpareparts.splice(index, 1);
        },

        filteredSpareparts(search) {
            if (!search) return sparepartsList;
            const s = search.toLowerCase();
            return sparepartsList.filter(sp => sp.name.toLowerCase().includes(s));
        },

        selectSparepart(index, sp) {
            const item = this.selectedSpareparts[index];
            item.id = sp.id;
            item.name = sp.name;
            item.price = sp.sell_price;
            item.subtotal = item.price * item.qty;
            item.search = '';
            item.showDropdown = false;
        },

        updateSubtotal(index) {
            const item = this.selectedSpareparts[index];
            item.subtotal = (item.price || 0) * (item.qty || 0);
        },

        numberFormat(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    }
}
</script>
@endpush
@endsection
