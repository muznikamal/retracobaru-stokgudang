@extends('layouts.app')
@section('title', 'Edit Barang Masuk')
@section('content')
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <header class="flex items-center justify-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Barang Masuk</h2>
            </header>

            <form action="{{ route('barang-masuk.update', $barangMasuk->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Petugas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama PO/Cost</label>
                    <input type="text" name="nama_petugas" required
                        value="{{ old('nama_petugas', $barangMasuk->nama_petugas) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Pilih Barang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <x-dropdown-search name="barang_id" label="Barang" :options="$barang->map(
                        fn($b) => [
                            'id' => $b->id,
                            'text' => $b->nama_barang . ' (Stok: ' . $b->stok . ' ' . $b->satuan . ')',
                        ],
                    )" :selectedId="$barangMasuk->barang_id" required
                        placeholder="Cari barang..." emptyMessage="Tidak ada barang ditemukan" class="mb-4" />
                </div>


                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" min="1" required
                        value="{{ old('jumlah', $barangMasuk->jumlah) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="3"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Opsional...">{{ old('catatan', $barangMasuk->catatan) }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('barang-masuk.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
