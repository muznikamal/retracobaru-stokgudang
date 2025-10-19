@extends('layouts.app')
@section('title', 'Tambah Barang Keluar')
@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl p-6">
            <header class="flex items-center justify-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="material-icons text-red-600">inventory</span>
                    Tambah Barang Keluar
                </h2>
            </header>

            <form action="{{ route('barang-keluar.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Petugas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Nama PO/Cost</label>
                    <input type="text" name="nama_petugas" required
                        class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Masukkan nama PO atau cost center">
                </div>

                <!-- Container Barang -->
                <div id="barangContainer" class="space-y-5">
                    <div class="barang-item border border-gray-200 rounded-xl p-4 relative bg-gray-50">
                        <button type="button"
                            class="remove-barang absolute top-2 right-2 hidden text-red-500 hover:text-red-600 transition">
                            <span class="material-icons">delete</span>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Dropdown Barang --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Barang</label>
                                <x-dropdown-search name="barang_id[]" label="Barang" :options="$barang->map(
                                    fn($b) => [
                                        'id' => $b->id,
                                        'text' => $b->nama_barang . ' (Stok: ' . $b->stok . ' ' . $b->satuan . ')',
                                    ],
                                )" required
                                    placeholder="Cari barang..." emptyMessage="Tidak ada barang ditemukan" class="mt-1" />
                            </div>
                            
                            {{-- Jumlah --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Jumlah</label>
                                <input type="number" name="jumlah[]" min="1" required
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Masukkan jumlah barang">
                            </div>

                            {{-- Dropdown Tipe Keluar --}}
                            <div x-data="{
                                open: false,
                                selectedValue: '',
                                selectedLabel: '-- Pilih Tipe --',
                                options: [
                                    { value: 'penjualan', label: 'Penjualan' },
                                    { value: 'kendala', label: 'Kendala' }
                                ]
                            }" class="relative">
                                <label class="block text-sm font-semibold text-gray-700">Tipe Keluar</label>

                                <div @click="open = !open"
                                    class="flex justify-between items-center mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 bg-white cursor-pointer shadow-sm hover:border-emerald-400 focus:ring-2 focus:ring-emerald-500 transition">
                                    <span x-text="selectedLabel"
                                        :class="selectedValue ? 'text-gray-700' : 'text-gray-400'"></span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <template x-for="option in options" :key="option.value">
                                        <div @click="selectedValue = option.value; selectedLabel = option.label; open = false"
                                            class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                                            :class="selectedValue == option.value ?
                                                'bg-emerald-50 text-emerald-700 font-medium' : ''"
                                            x-text="option.label">
                                        </div>
                                    </template>
                                </div>

                                <input type="hidden" name="tipe_keluar[]" :value="selectedValue" required>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Catatan</label>
                                <input type="text" name="catatan[]" placeholder="Opsional"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tombol Tambah Barang -->
                <button type="button" id="addBarang"
                    class="flex items-center gap-1 text-emerald-600 font-semibold hover:text-emerald-700 transition">
                    <span class="material-icons text-lg">add_circle</span>
                    Tambah Barang
                </button>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-4 pt-4">
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

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("barangContainer");
            const addBtn = document.getElementById("addBarang");

            addBtn.addEventListener("click", function() {
                const newItem = container.firstElementChild.cloneNode(true);
                newItem.querySelectorAll("input, select").forEach(el => el.value = "");
                newItem.querySelector(".remove-barang").classList.remove("hidden");
                container.appendChild(newItem);
            });

            container.addEventListener("click", function(e) {
                if (e.target.closest(".remove-barang")) {
                    e.target.closest(".barang-item").remove();
                }
            });
        });
    </script>
@endpush
