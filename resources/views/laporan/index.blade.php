@extends('layouts.app')
@section('title', 'Laporan Barang')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-xl p-6">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">bar_chart</span>
                    Laporan Barang
                </h2>
            </div>

            {{-- FILTER --}}
            <form method="GET"
                class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-medium">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-medium">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:shadow-lg hover:scale-[1.02] transition font-medium flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-base">search</span>
                        Tampilkan
                    </button>
                </div>
                <div class="flex items-end justify-end sm:col-span-3 md:col-span-1 gap-2">
                    <a href="{{ route('laporan.export.excel', ['from' => $from, 'to' => $to]) }}"
                        class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:shadow-lg hover:scale-[1.02] transition font-medium">
                        <span class="material-symbols-outlined text-base">file_save</span> Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', ['from' => $from, 'to' => $to]) }}"
                        class="flex items-center gap-1 px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:shadow-lg hover:scale-[1.02] transition font-medium">
                        <span class="material-symbols-outlined text-base">picture_as_pdf</span> PDF
                    </a>
                </div>
            </form>

            {{-- TABEL --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-emerald-600 text-white text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Barang</th>
                            <th class="px-4 py-3 text-center">Total Masuk</th>
                            <th class="px-4 py-3 text-center">Penjualan</th>
                            <th class="px-4 py-3 text-center">Kendala</th>
                            <th class="px-4 py-3 text-center">Total Keluar</th>
                            <th class="px-4 py-3 text-center">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium">{{ $item['nama_barang'] }}</td>
                                <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $item['total_masuk'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-blue-600 font-semibold">{{ $item['total_penjualan'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-yellow-600 font-semibold">{{ $item['total_kendala'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-red-600 font-semibold">{{ $item['total_keluar'] }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $item['stok'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    <span class="material-symbols-outlined align-middle text-gray-400 text-xl">info</span>
                                    Tidak ada data untuk periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- RINGKASAN TOTAL --}}
            @if ($data->count() > 0)
                <div
                    class="mt-8 bg-gradient-to-br from-emerald-50 to-white rounded-2xl shadow-lg border border-emerald-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-emerald-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">analytics</span>
                            Ringkasan Total
                        </h3>
                        <span class="text-sm text-gray-500">Periode: {{ $from }} - {{ $to }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5">
                        {{-- Total Masuk --}}
                        <div
                            class="relative bg-white hover:bg-emerald-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Masuk</p>
                                    <h4 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalSummary['masuk'] }}</h4>
                                </div>
                                <span
                                    class="material-symbols-outlined text-emerald-500 text-3xl group-hover:scale-110 transition-transform">inventory_2</span>
                            </div>
                        </div>

                        {{-- Penjualan --}}
                        <div
                            class="relative bg-white hover:bg-blue-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Penjualan</p>
                                    <h4 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalSummary['penjualan'] }}</h4>
                                </div>
                                <span
                                    class="material-symbols-outlined text-blue-500 text-3xl group-hover:scale-110 transition-transform">shopping_cart</span>
                            </div>
                        </div>

                        {{-- Kendala --}}
                        <div
                            class="relative bg-white hover:bg-amber-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Kendala</p>
                                    <h4 class="text-2xl font-bold text-amber-600 mt-1">{{ $totalSummary['kendala'] }}</h4>
                                </div>
                                <span
                                    class="material-symbols-outlined text-amber-500 text-3xl group-hover:scale-110 transition-transform">report_problem</span>
                            </div>
                        </div>

                        {{-- Total Keluar --}}
                        <div
                            class="relative bg-white hover:bg-rose-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Keluar</p>
                                    <h4 class="text-2xl font-bold text-rose-600 mt-1">{{ $totalSummary['keluar'] }}</h4>
                                </div>
                                <span
                                    class="material-symbols-outlined text-rose-500 text-3xl group-hover:scale-110 transition-transform">logout</span>
                            </div>
                        </div>

                        {{-- Stok Akhir --}}
                        <div
                            class="relative bg-white hover:bg-gray-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Stok Akhir</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSummary['stok'] }}</h4>
                                </div>
                                <span
                                    class="material-symbols-outlined text-gray-600 text-3xl group-hover:scale-110 transition-transform">inventory</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
@endsection
