@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
    <header class="flex items-center justify-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Barang</h2>
    </header>

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama Barang -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input 
                type="text" 
                name="nama_barang" 
                value="{{ old('nama_barang', $barang->nama_barang) }}"
                class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3"
                required>
        </div>

        <!-- Stok -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
            <input 
                type="number" 
                name="stok" 
                value="{{ old('stok', $barang->stok) }}"
                min="0"
                class="w-full rounded-xl bg-gray-200 border-gray-300 disabled:opacity-70 disabled:cursor-not-allowed p-3" disabled>
        </div>

        <!-- Satuan -->
        <div x-data="{ open: false, selected: '{{ old('satuan', $barang->satuan ?? 'Pilih Satuan') }}' }" class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>

            <!-- Tombol dropdown -->
            <div 
                @click="open = !open"
                class="flex justify-between items-center w-full border border-gray-300 rounded-xl p-3 cursor-pointer bg-white shadow-sm 
                       hover:border-emerald-400 focus:ring-2 focus:ring-emerald-500 transition">
                <span 
                    x-text="selected" 
                    :class="selected === 'Pilih Satuan' ? 'text-gray-400' : 'text-gray-700'">
                </span>
                <span class="material-symbols-outlined text-gray-400">arrow_drop_down</span>
            </div>

            <!-- Daftar opsi -->
            <div 
                x-show="open" 
                @click.away="open = false" 
                x-transition
                class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                
                <template 
                    x-for="option in ['Pcs', 'Box', 'Liter', 'Kilogram (Kg)', 'Sak', 'Lembar', 'Meter', 'Batang', 'Roll', 'Kaleng', 'Buah', 'Pak', 'Set', 'Lainnya...']" 
                    :key="option">
                    <div 
                        @click="selected = option; open = false"
                        class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                        x-text="option">
                    </div>
                </template>
            </div>

            <!-- Input manual -->
            <div x-show="selected === 'Lainnya...'" x-transition class="mt-3">
                <input 
                    type="text" 
                    name="satuan_manual" 
                    value="{{ old('satuan_manual', $barang->satuan_manual ?? '') }}"
                    placeholder="Masukkan satuan baru..."
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3 placeholder-gray-400 transition">
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="satuan" :value="selected === 'Pilih Satuan' ? '' : selected">
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <input 
                type="text" 
                name="kategori" 
                value="{{ old('kategori', $barang->kategori) }}"
                class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3">
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-4">
            <a 
                href="{{ route('barang.index') }}"
                class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                Batal
            </a>
            <button 
                type="submit"
                class="px-5 py-2 bg-emerald-600 text-white rounded-xl shadow hover:bg-emerald-700 transition">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
