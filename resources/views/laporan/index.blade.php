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
                            <th class="px-4 py-3 text-center bg-green-700">Total Masuk</th>
                            <th class="px-4 py-3 text-center bg-red-800">Total Penjualan</th>
                            <th class="px-4 py-3 text-center bg-red-800">Total Kendala</th>
                            <th class="px-4 py-3 text-center bg-red-800">Total Keluar</th>
                            <th class="px-4 py-3 text-center bg-yellow-600">Selisih Opname</th>
                            <th class="px-4 py-3 text-center bg-black">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-t font-bold hover:bg-gray-50 transition">
                                <td class="px-4 py-3">{{ $item['nama_barang'] }}</td>
                                <td class="px-4 py-3 text-center text-green-600 ">{{ $item['total_masuk'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-blue-600 ">{{ $item['total_penjualan'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-amber-600 ">{{ $item['total_kendala'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-red-600 ">{{ $item['total_keluar'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-yellow-600 ">
                                    {{ $item['total_selisih_opname'] }}</td>
                                <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $item['stok'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>

            {{-- RINGKASAN TOTAL --}}
            {{-- @if ($data->count() > 0)
                <div
                    class="mt-8 bg-gradient-to-br from-emerald-50 to-white rounded-2xl shadow-lg border border-emerald-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-emerald-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">analytics</span>
                            Ringkasan Total
                        </h3>
                        <span class="text-sm text-gray-500">Periode: {{ $from }} - {{ $to }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-5"> --}}
                        {{-- Total Masuk --}}
                        {{-- <x-summary-card color="emerald" icon="inventory_2" label="Total Masuk" :value="$totalSummary['masuk']" /> --}}
                        {{-- Penjualan --}}
                        {{-- <x-summary-card color="blue" icon="shopping_cart" label="Penjualan" :value="$totalSummary['penjualan']" /> --}}
                        {{-- Kendala --}}
                        {{-- <x-summary-card color="amber" icon="report_problem" label="Kendala" :value="$totalSummary['kendala']" /> --}}
                        {{-- Total Keluar --}}
                        {{-- <x-summary-card color="rose" icon="logout" label="Total Keluar" :value="$totalSummary['keluar']" /> --}}
                        {{-- Selisih --}}
                        {{-- <x-summary-card color="amber" icon="difference" label="Selisih Total" :value="$totalSummary['selisih'] ?? 0" /> --}}
                        {{-- Stok Akhir --}}
                        {{-- <x-summary-card color="gray" icon="inventory" label="Stok Akhir" :value="$totalSummary['stok']" /> --}}
                    {{-- </div>
                </div>
            @endif --}}
        </div>
    </div>
@endsection
