@extends('layouts.app')
@section('title', 'Tambah Barang Masuk')
@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl p-6">
            <header class="flex items-center justify-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="material-icons text-emerald-600">inventory_2</span>
                    Tambah Barang Masuk
                </h2>
            </header>

            <form action="{{ route('barang-masuk.store') }}" method="POST" class="space-y-6">
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


                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Jumlah</label>
                                <input type="number" name="jumlah[]" min="1" required
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Masukkan jumlah barang">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700">Catatan</label>
                                <input type="text" name="catatan[]"
                                    placeholder="Opsional, tambahkan catatan..."
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
                    <a href="{{ route('barang-masuk.index') }}"
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

            // Tambah barang baru
            addBtn.addEventListener("click", function() {
                const newItem = container.firstElementChild.cloneNode(true);
                newItem.querySelectorAll("input, select").forEach(el => el.value = "");
                newItem.querySelector(".remove-barang").classList.remove("hidden");
                container.appendChild(newItem);
            });

            // Hapus barang
            container.addEventListener("click", function(e) {
                if (e.target.closest(".remove-barang")) {
                    e.target.closest(".barang-item").remove();
                }
            });
        });
    </script>
@endpush
