@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-4 px-2 lg:px-8">
        <div class="bg-white shadow rounded-xl p-4 sm:p-6">
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    📦 Data Barang Gudang
                </h2>
                @can('barang.create')
                    <a href="{{ route('barang.create') }}"
                        class="px-4 py-2 sm:px-5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg shadow hover:scale-105 transition text-sm sm:text-base">
                        + Tambah Barang
                    </a>
                @endcan
            </div>

            {{-- FILTER & SEARCH --}}
            <div
                class="flex flex-col lg:flex-row justify-between items-center gap-3 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                {{-- Search --}}
                <form action="{{ route('barang.index') }}" method="GET" class="relative w-full lg:w-1/3">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-500 text-base">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                    <button type="submit"
                        class="absolute right-0 top-0 h-full px-4 bg-emerald-600 text-white text-sm font-medium rounded-r-lg hover:bg-emerald-700 transition">
                        Cari
                    </button>
                </form>

                {{-- Filter kategori --}}
                <form action="{{ route('barang.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 w-full lg:w-2/3 justify-between lg:justify-end text-sm">
                    <label class="text-gray-700 font-semibold">Filter Kategori:</label>
                    <div class="relative inline-block w-full sm:w-48">
                        <select name="kategori" onchange="this.form.submit()"
                            class="appearance-none w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-400 bg-white pr-8">
                            <option value="">Semua</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori }}"
                                    {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst($kategori) }}
                                </option>
                            @endforeach
                        </select>
                        <span
                            class="material-symbols-outlined absolute right-2 top-2.5 text-gray-400 text-base pointer-events-none">
                            expand_more
                        </span>
                    </div>
                    <a href="{{ route('barang.index') }}"
                        class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                        Reset
                    </a>
                </form>
            </div>

            {{-- TABEL BARANG --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                {{-- Desktop View --}}
                <table class="hidden md:table w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-center">NO</th>
                            <th class="p-3 text-center">
                                <a href="{{ route(
                                    'barang.index',
                                    array_merge(request()->query(), [
                                        'sort' => request('sort') === 'nama_asc' ? 'nama_desc' : 'nama_asc',
                                    ]),
                                ) }}"
                                    class="flex items-center gap-1">
                                    NAMA BARANG
                                    <span class="material-symbols-outlined text-xs !text-[16px]">
                                        @if (request('sort') === 'nama_asc')
                                            arrow_upward
                                        @elseif(request('sort') === 'nama_desc')
                                            arrow_downward
                                        @else
                                            unfold_more
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="p-3 text-center">KATEGORI</th>
                            <th class="p-3 text-center">SATUAN</th>
                            <th class="p-3 text-center">
                                <a href="{{ route(
                                    'barang.index',
                                    array_merge(request()->query(), [
                                        'sort' => request('sort') === 'stok_asc' ? 'stok_desc' : 'stok_asc',
                                    ]),
                                ) }}"
                                    class="flex items-center justify-center gap-1">
                                    STOK
                                    <span class="material-symbols-outlined text-xs !text-[16px]">
                                        @if (request('sort') === 'stok_asc')
                                            arrow_upward
                                        @elseif(request('sort') === 'stok_desc')
                                            arrow_downward
                                        @else
                                            unfold_more
                                        @endif
                                    </span>
                                </a>
                            </th>
                            @role('admin')
                                <th class="p-3 text-center">AKSI</th>
                            @endrole
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse($barang as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-center">{{ $barang->firstItem() + $index }}</td>
                                <td class="p-3 font-semibold text-gray-800">{{ $item->nama_barang }}</td>
                                <td class="p-3 text-center">{{ $item->kategori ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $item->satuan }}</td>
                                <td class="p-3 text-center">
                                    <span
                                        class="px-3 py-1.5 rounded-full font-bold text-lg {{ $item->stok <= 5 ? ' text-red-700' : ' text-emerald-700' }}">
                                        {{ $item->stok }}
                                    </span>
                                </td>
                                {{-- @role('admin') --}}
                                @canany(['barang.edit', 'barang.delete'])
                                    <td class="p-3 flex justify-center gap-2">
                                        @can('barang.edit')
                                            <a href="{{ route('barang.edit', $item->id) }}"
                                                class="p-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </a>
                                        @endcan
                                        @can('barang.delete')
                                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus data ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                @endcanany
                                {{-- @endrole --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-3 text-center text-gray-500">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile View --}}
                <div class="md:hidden divide-y divide-gray-200 text-gray-700">
                    @forelse($barang as $index => $item)
                        <div x-data="{ open: false }"
                            class="p-4 flex flex-col gap-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="open = !open">

                            {{-- Header Utama --}}
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium text-gray-800">
                                    {{ $item->nama_barang }}
                                </h3>
                                <span
                                    class="font-semibold text-sm {{ $item->stok <= 5 ? 'text-red-600' : 'text-emerald-700' }}">
                                    {{ $item->stok }} stk
                                </span>
                            </div>

                            {{-- Info Ringkas --}}
                            <div class="text-sm text-gray-600">
                                <p>Kategori: <span class="font-medium">{{ $item->kategori ?? '-' }}</span></p>
                                <p>Satuan: <span class="font-medium">{{ $item->satuan }}</span></p>
                            </div>

                            {{-- Tombol Aksi (Expand/Collapse) --}}
                            <div x-show="open" x-transition class="flex gap-3 mt-3 border-t pt-3 justify-end">
                                {{-- @role('admin') --}}
                                @can('barang.edit')
                                    <a href="{{ route('barang.edit', $item->id) }}"
                                        class="flex items-center gap-1 px-3 py-1.5 bg-blue-500 text-white rounded-md text-sm shadow hover:bg-blue-600 transition">
                                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                                    </a>
                                @endcan

                                @can('barang.delete')
                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus data ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white rounded-md text-sm shadow hover:bg-red-600 transition">
                                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                        </button>
                                    </form>
                                @endcan
                                {{-- @endrole --}}
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">Tidak ada data ditemukan</div>
                    @endforelse
                </div>

            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $barang->links() }}
            </div>
        </div>
    </div>
@endsection
