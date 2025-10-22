@extends('layouts.app')
@section('title', 'Data Barang Masuk')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-xl p-6" x-data="{ openAdvanced: false }">
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    Data Barang Masuk
                </h2>
                @role('staff|admin')
                    <a href="{{ route('barang-masuk.create') }}"
                        class="px-5 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg shadow hover:scale-105 transition">
                        + Tambah Barang
                    </a>
                @endrole
            </div>

            {{-- FILTER AREA --}}
            <div
                class="flex flex-col lg:flex-row justify-between items-center gap-3 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">

                {{-- Search --}}
                <form action="{{ route('barang-masuk.index') }}" method="GET" class="relative w-full lg:w-1/3">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-500 text-base">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari barang, nama PO, atau user..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                    <button type="submit"
                        class="absolute right-0 top-0 h-full px-4 bg-emerald-600 text-white text-sm font-medium rounded-r-lg hover:bg-emerald-700 transition">
                        Cari
                    </button>
                </form>

                {{-- Filter tanggal --}}
                <form action="{{ route('barang-masuk.index') }}" method="GET"
                    class="hidden md:flex flex-wrap items-center gap-3 w-full lg:w-2/3 justify-end">
                    <label class="text-gray-700 text-sm font-semibold">Filter Tanggal:</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="border rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-emerald-400 text-sm">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="border rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-emerald-400 text-sm">

                    <button type="submit"
                        class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 text-sm">
                        Terapkan
                    </button>
                    <a href="{{ route('barang-masuk.index') }}"
                        class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                        Reset
                    </a>
                </form>
                {{-- Tombol filter lanjutan (MOBILE ONLY) --}}
                <button @click="openAdvanced = true"
                    class="flex md:hidden items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg shadow hover:bg-emerald-600 text-sm">
                    <span class="material-symbols-outlined text-sm">tune</span> Filter Lanjutan
                </button>
            </div>

            {{-- TABEL --}}
            <div class="overflow-visible border border-gray-200 rounded-lg relative hidden md:block">
                <table class="min-w-full table-auto text-sm relative z-10">
                    <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                        <tr>
                            <th class="p-3 text-center">NO</th>

                            {{-- NAMA PO --}}
                            <th class="p-3 text-center relative" x-data="{ open: false }">
                                <div class="inline-block">
                                    <button type="button" @click="open = !open"
                                        class="flex items-center gap-1 cursor-pointer select-none">
                                        NAMA PO/COST
                                        <span class="material-symbols-outlined text-xs">arrow_drop_down</span>
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute left-0 mt-1 w-52 bg-white border rounded-lg shadow-xl z-50 max-h-56 overflow-y-auto">
                                        <a href="{{ route('barang-masuk.index') }}"
                                            class="block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm">-
                                            Semua -</a>
                                        @foreach ($namaPetugasList as $petugas)
                                            <a href="{{ route('barang-masuk.index', array_merge(request()->except('filter_petugas'), ['filter_petugas' => $petugas])) }}"
                                                class="block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm {{ request('filter_petugas') == $petugas ? 'bg-emerald-200 font-semibold' : '' }}">
                                                {{ $petugas }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </th>

                            {{-- BARANG --}}
                            <th class="p-3 text-center relative" x-data="{ open: false }">
                                <div class="inline-block">
                                    <button type="button" @click="open = !open"
                                        class="flex items-center gap-1 cursor-pointer select-none">
                                        BARANG
                                        <span class="material-symbols-outlined text-xs">arrow_drop_down</span>
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute left-0 mt-1 w-56 bg-white border rounded-lg shadow-xl z-50 max-h-56 overflow-y-auto">
                                        <a href="{{ route('barang-masuk.index') }}"
                                            class="block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm">-
                                            Semua -</a>
                                        @foreach ($barangList as $barang)
                                            <a href="{{ route('barang-masuk.index', array_merge(request()->except('filter_barang'), ['filter_barang' => $barang])) }}"
                                                class="block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm {{ request('filter_barang') == $barang ? 'bg-emerald-200 font-semibold' : '' }}">
                                                {{ $barang }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </th>

                            {{-- JUMLAH --}}
                            <th class="p-3 text-center">
                                <a href="{{ route('barang-masuk.index', array_merge(request()->except('sort'), ['sort' => request('sort') == 'jumlah_asc' ? 'jumlah_desc' : 'jumlah_asc'])) }}"
                                    class=" flex items-center justify-center gap-1 select-none cursor-pointer">
                                    JUMLAH
                                    @if (request('sort') == 'jumlah_asc')
                                        <span class="material-symbols-outlined text-xs !text-[16px]">arrow_upward</span>
                                    @elseif(request('sort') == 'jumlah_desc')
                                        <span class="material-symbols-outlined text-xs !text-[16px]">arrow_downward</span>
                                    @else
                                        <span class="material-symbols-outlined text-xs text-gray-400">unfold_more</span>
                                    @endif
                                </a>
                            </th>

                            {{-- CATATAN --}}
                            <th class="p-3 text-center">CATATAN</th>

                            {{-- USER --}}
                            <th class="p-3 text-center relative" x-data="{ open: false }">
                                <div class="inline-block">
                                    <button type="button" @click="open = !open"
                                        class="flex items-center gap-1 cursor-pointer select-none">
                                        USER INPUT
                                        <span class="material-symbols-outlined text-xs">arrow_drop_down</span>
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute left-0 mt-1 w-52 bg-white border rounded-lg shadow-xl z-50 max-h-56 overflow-y-auto">
                                        <a href="{{ route('barang-masuk.index') }}"
                                            class=" block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm">-
                                            Semua -</a>
                                        @foreach ($userList as $user)
                                            <a href="{{ route('barang-masuk.index', array_merge(request()->except('filter_user'), ['filter_user' => $user])) }}"
                                                class="block px-3 py-1 font-normal text-gray-700 hover:bg-emerald-100 text-sm {{ request('filter_user') == $user ? 'bg-emerald-200 font-semibold' : '' }}">
                                                {{ $user }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </th>

                            <th class="p-3 text-center">TANGGAL</th>
                            <th class="p-3 text-center">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y text-gray-700">
                        @forelse ($data as $i => $row)
                        @php
                            $isAdmin = auth()->user()->hasRole('admin');
                            $isStaff = auth()->user()->hasRole('staff');

                            $canEdit =
                                $isAdmin ||
                                ($isStaff &&
                                    $row->user_id == auth()->id() &&
                                    now()->diffInHours($row->created_at->setTimezone(config('app.timezone'))) <= 24);
                        @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-center">{{ $i + 1 }}</td>
                                <td class="p-3">{{ $row->nama_petugas }}</td>
                                <td class="p-3">{{ $row->barang->nama_barang }}</td>
                                <td class="p-3 text-center">{{ $row->jumlah }}</td>
                                <td class="p-3 text-center">
                                    @if ($row->catatan)
                                        <button onclick='showCatatan(@json($row->catatan))'
                                            class="text-gray-400 hover:text-gray-800">
                                            <span class="material-symbols-outlined text-sm align-middle">visibility</span>
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-left">{{ $row->user->name }}</td>
                                <td class="p-3 text-center">{{ $row->created_at->format('d M Y') }}</td>

                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-3 mt-3">
                                        {{-- Tombol Edit --}}
                                        @if ($canEdit)
                                            <a href="{{ route('barang-masuk.edit', $row->id) }}"
                                                class="p-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm transition duration-150">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </a>
                                        @else
                                            <button disabled
                                                class="p-1.5 bg-gray-300 text-gray-500 rounded-md text-sm cursor-not-allowed"
                                                title="Hanya dapat mengedit data milik sendiri (maks. 24 jam)">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endif

                                        {{-- Tombol Hapus (hanya admin) --}}
                                        @if ($isAdmin)
                                            <form action="{{ route('barang-masuk.destroy', $row->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin hapus data?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-3 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW --}}
            <div class="md:hidden divide-y divide-gray-200 text-gray-700">
                @forelse ($data as $i => $row)
                    <div x-data="{ open: false }" class="p-4 hover:bg-gray-50 transition">
                        {{-- Header baris utama --}}
                        <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $row->barang->nama_barang }}</h3>
                                <p class="text-xs text-gray-500">{{ $row->created_at->format('d M Y') }}</p>
                            </div>
                            <span
                                class="px-3 py-1.5 rounded-full text-right {{ $row->jumlah <= 5 ? ' text-red-700' : ' text-emerald-700' }} font-semibold text-sm }">
                                {{ $row->jumlah }}
                            </span>
                        </div>

                        {{-- Detail tersembunyi --}}
                        <div x-show="open" x-collapse class="mt-3 text-sm text-gray-600 space-y-1">
                            <p>Nama PO/Cost: <span class="font-medium">{{ $row->nama_petugas }}</span></p>
                            <p>Jumlah: {{ $row->jumlah }}</p>
                            <p>User Input: {{ $row->user->name }}</p>
                            <p>Tanggal: {{ $row->created_at->format('d M Y') }}</p>

                            @if ($row->catatan)
                                <button onclick='showCatatan(@json($row->catatan))'
                                    class="text-gray-500 hover:text-gray-800 text-sm flex items-center gap-1 mt-2">
                                    <span class="material-symbols-outlined text-sm">visibility</span> Lihat Catatan
                                </button>
                            @endif

                            @if (auth()->user()->hasRole('admin') ||
                                    (auth()->user()->hasRole('staff') &&
                                        $row->user_id == auth()->id() &&
                                        now()->diffInHours($row->created_at) <= 24))
                                <div class="flex gap-3 mt-3">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('barang-masuk.edit', $row->id) }}"
                                        class="flex items-center gap-1 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm transition duration-150">
                                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                                    </a>

                                    {{-- Tombol Hapus (tetap hanya admin) --}}
                                    @if (auth()->user()->hasRole('admin'))
                                        <form action="{{ route('barang-masuk.destroy', $row->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin hapus data?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition">
                                                <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                {{-- Tampilan disable edit --}}
                                <div class="gap-3 mt-3">
                                    <button disabled
                                        class="flex  gap-1 px-3 py-1 bg-gray-300 text-gray-500 rounded-md text-sm cursor-not-allowed"
                                        title="Anda tidak memiliki izin untuk mengedit data ini">
                                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">Belum ada data</div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $data->links() }}
            </div>

            {{-- MODAL CATATAN --}}
            <div id="catatanModal"
                class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                <div class="bg-white p-5 rounded-lg shadow-lg w-96">
                    <h3 class="text-lg font-semibold mb-3">Catatan Barang Masuk</h3>
                    <p id="catatanText" class="text-gray-700 mb-4"></p>
                    <button onclick="closeCatatan()"
                        class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">Tutup</button>
                </div>
            </div>

            {{-- MODAL FILTER LANJUTAN (MOBILE ONLY) --}}
            <div x-show="openAdvanced" x-transition.opacity.duration.200ms x-init="$watch('openAdvanced', val => document.body.style.overflow = val ? 'hidden' : 'auto')"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div @click.away="openAdvanced = false"
                    class="relative bg-white w-11/12 max-w-md rounded-2xl p-5 shadow-lg overflow-y-auto max-h-[90vh]"> <!-- Tambahkan ini -->
                    {{-- Tombol Close --}}
                    <button type="button" @click="openAdvanced = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition">
                        ✕
                    </button>

                    <h3 class="text-lg font-semibold mb-5 text-gray-800 text-center">Filter Lanjutan</h3>

                    {{-- Filter form --}}
                    <form action="{{ route('barang-masuk.index') }}" method="GET" class="space-y-4 text-sm">

                        {{-- Tanggal --}}
                        <div>
                            <label class="font-medium text-gray-700">Tanggal</label>
                            <div class="flex items-center gap-2 mt-1">
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                    class="border rounded-xl px-3 py-2 w-1/2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <span class="text-gray-600">s/d</span>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                                    class="border rounded-xl px-3 py-2 w-1/2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        {{-- Nama PO --}}
                        <x-select-dropdown-search name="filter_petugas" label="Nama PO/Cost" :options="$namaPetugasList->map(fn($p) => ['value' => $p, 'label' => $p])" :selected="request('filter_petugas')"
                            placeholder="Semua" />

                        {{-- Barang --}}
                        <x-select-dropdown-search name="filter_barang" label="Barang" :options="$barangList->map(fn($b) => ['value' => $b, 'label' => $b])" :selected="request('filter_barang')"
                            placeholder="Semua" />

                        {{-- User Input --}}
                        <x-select-dropdown-search name="filter_user" label="User Input" :options="$userList->map(fn($u) => ['value' => $u, 'label' => $u])" :selected="request('filter_user')"
                            placeholder="Semua" />

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('barang-masuk.index') }}"
                                class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition">
                                Reset
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    {{-- SCRIPT --}}
    <script>
        function showCatatan(catatan) {
            const modal = document.getElementById('catatanModal');
            const catatanText = document.getElementById('catatanText');
            catatanText.innerHTML = catatan.replace(/\n/g, '<br>');
            modal.classList.remove('hidden');
        }

        function closeCatatan() {
            document.getElementById('catatanModal').classList.add('hidden');
        }
    </script>
@endsection
