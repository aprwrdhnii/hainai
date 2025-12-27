@extends('layouts.app')
@section('title', 'Edit Servis')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Servis</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Perbarui data servis {{ $service->service_number }}</p>
        </div>
        <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('services.update', $service) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="p-6 space-y-6">
                
                <!-- Section 1: Kendaraan & Mekanik -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kendaraan</label>
                        <select name="vehicle_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ $service->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->name }} ({{ $vehicle->customer->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Masuk</label>
                        <input type="date" name="service_date" value="{{ $service->service_date->format('Y-m-d') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Masuk</label>
                        <input type="time" name="service_time" value="{{ $service->service_time ? \Carbon\Carbon::parse($service->service_time)->format('H:i') : '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montir Penanggung Jawab</label>
                        <div class="relative">
                            <select name="mechanic_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition appearance-none">
                                <option value="">Pilih Montir...</option>
                                @foreach($mechanics as $mechanic)
                                <option value="{{ $mechanic->id }}" {{ $service->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                    {{ $mechanic->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Section 2: Detail Pengerjaan -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keluhan Pelanggan</label>
                        <textarea name="complaint" rows="2" placeholder="Deskripsi keluhan..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ $service->complaint }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diagnosis / Catatan Mekanik</label>
                        <textarea name="diagnosis" rows="2" placeholder="Hasil pemeriksaan..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ $service->diagnosis }}</textarea>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Section 3: Biaya & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Biaya Jasa (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                            <input type="number" name="labor_cost" value="{{ $service->labor_cost }}" min="0"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="status" value="bon" {{ $service->status == 'bon' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 transition text-center hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <i class="bi bi-receipt text-xl mb-1 block text-gray-400 peer-checked:text-orange-500"></i>
                                    <span class="font-semibold text-gray-600 dark:text-gray-300 peer-checked:text-orange-600 dark:peer-checked:text-orange-400">Bon</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="status" value="lunas" {{ $service->status == 'lunas' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition text-center hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <i class="bi bi-check-circle text-xl mb-1 block text-gray-400 peer-checked:text-green-500"></i>
                                    <span class="font-semibold text-gray-600 dark:text-gray-300 peer-checked:text-green-600 dark:peer-checked:text-green-400">Lunas</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Action -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('services.show', $service) }}" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
