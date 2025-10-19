@extends('layouts.app')
@section('title', 'Edit Barang Keluar')
@section('content')
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <header class="flex items-center justify-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Barang Keluar</h2>
            </header>

            <form action="{{ route('barang-keluar.update', $barangKeluar->id) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama PO/Cost</label>
                    <input type="text" name="nama_petugas" value="{{ $barangKeluar->nama_petugas }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                {{-- Dropdown Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <x-dropdown-search name="barang_id" label="Barang" :options="$barang->map(
                        fn($b) => [
                            'id' => $b->id,
                            'text' => $b->nama_barang . ' (Stok: ' . $b->stok . ' ' . $b->satuan . ')',
                        ],
                    )" :selectedId="$barangKeluar->barang_id" required
                        placeholder="Cari barang..." emptyMessage="Tidak ada barang ditemukan" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ $barangKeluar->jumlah }}" min="1"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                {{-- Dropdown Tipe Keluar --}}
                <div x-data="{
                    open: false,
                    selectedValue: '{{ $barangKeluar->tipe_keluar }}',
                    selectedLabel: '',
                    options: [
                        { value: 'penjualan', label: 'Penjualan' },
                        { value: 'kendala', label: 'Kendala' },
                    ],
                    init() {
                        const selected = this.options.find(o => o.value == this.selectedValue);
                        this.selectedLabel = selected ? selected.label : '-- Pilih Tipe --';
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Keluar</label>

                    <!-- Trigger -->
                    <div @click="open = !open"
                        class="flex justify-between items-center w-full border border-gray-300 rounded-lg px-3 py-2 bg-white cursor-pointer shadow-sm hover:border-emerald-400 focus:ring-2 focus:ring-emerald-500 transition">
                        <span x-text="selectedLabel" :class="selectedValue ? 'text-gray-700' : 'text-gray-400'"></span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Options -->
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                        <template x-for="option in options" :key="option.value">
                            <div @click="selectedValue = option.value; selectedLabel = option.label; open = false"
                                class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                                :class="selectedValue == option.value ? 'bg-emerald-50 text-emerald-700 font-medium' : ''"
                                x-text="option.label">
                            </div>
                        </template>
                    </div>

                    <!-- Hidden input -->
                    <input type="hidden" name="tipe_keluar" :value="selectedValue" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="3"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ $barangKeluar->catatan }}</textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('barang-keluar.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-emerald-600 text-white rounded-xl shadow hover:bg-emerald-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
