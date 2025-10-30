@extends('layouts.app')
@section('title', 'Opname Barang')
@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-6 transition-all" x-data="opnamePage()">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">fact_check</span>
                Opname Barang
            </h2>
            <p class="text-sm text-gray-500 mt-1">Lakukan pengecekan dan penyesuaian stok barang sesuai kondisi di lapangan</p>
        </div>
    </div>

    {{-- FORM INPUT OPNAME --}}
    <form action="{{ route('opname.store') }}" method="POST"
          class="bg-gray-50 border rounded-xl p-4 mb-6 flex flex-col sm:flex-row gap-3 sm:items-end">
        @csrf

        {{-- DROPDOWN BARANG --}}
        <div class="flex-1" x-ref="dropdownWrapper">
            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
            <x-dropdown-search
                name="barang_id"
                label="Barang"
                :options="$barangs->map(fn($b) => [
                    'id' => $b->id,
                    'text' => $b->nama_barang,
                    'stok' => $b->stok,
                ])"
                required
                placeholder="Cari barang..."
                emptyMessage="Tidak ada barang ditemukan"
                class="mt-1"
            />
        </div>

        {{-- STOK SISTEM --}}
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Sistem</label>
            <input type="text" x-model="selectedStock" readonly
                   class="w-full text-center bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- STOK FISIK --}}
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Fisik</label>
            <input type="number" name="stok_fisik" placeholder="Masukkan hasil stok fisik"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div>
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 shadow-sm transition">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                Simpan
            </button>
        </div>
    </form>

    {{-- TABEL OPNAME --}}
    <div class="overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-emerald-50 text-emerald-700 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Nama Barang</th>
                    <th class="px-4 py-3 text-center font-semibold">Stok Sistem</th>
                    <th class="px-4 py-3 text-center font-semibold">Stok Fisik</th>
                    <th class="px-4 py-3 text-center font-semibold">Selisih</th>
                    <th class="px-4 py-3 text-center font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opnames as $opname)
                    @php $selisih = $opname->stok_fisik - $opname->stok_sistem; @endphp
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $opname->barang->nama_barang }}</td>
                        <td class="px-4 py-3 text-center">{{ $opname->stok_sistem }}</td>
                        <td class="px-4 py-3 text-center">{{ $opname->stok_fisik }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="{{ $selisih > 0 ? 'text-green-600' : ($selisih < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                {{ $selisih }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $opname->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="editModal({{ $opname->id }}, '{{ $opname->stok_fisik }}')"
                                        class="flex items-center gap-1 px-3 py-1 bg-blue-400 hover:bg-blue-500 text-white rounded-lg text-xs font-medium shadow-sm transition">
                                    <span class="material-symbols-outlined text-sm">edit</span>Edit
                                </button>
                                <form action="{{ route('opname.destroy', $opname->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center gap-1 px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-medium shadow-sm transition">
                                        <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">Belum ada data opname</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL EDIT --}}
    <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50"
         x-show="showModal" x-cloak x-transition>
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">edit_note</span>Edit Opname Barang
            </h3>
            <form :action="`/opname/${opnameId}`" method="POST">
                @csrf
                @method('PUT')
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Fisik Baru</label>
                <input type="number" x-model="stokFisik" name="stok_fisik" required
                       class="w-full border-gray-300 rounded-lg mb-4 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showModal = false"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- SCRIPT ALPINE --}}
<script>
function opnamePage() {
    return {
        selectedStock: '-',
        options: @js(
            $barangs->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->nama_barang,
                'stok' => $b->stok,
            ])
        ),
        showModal: false,
        opnameId: null,
        stokFisik: '',

        init() {
            // Dengarkan event global dari dropdown
            window.addEventListener('dropdown-selected', e => {
                const { id } = e.detail;
                const selected = this.options.find(o => o.id == id);
                this.selectedStock = selected ? selected.stok : '-';
            });
        },

        editModal(id, stok) {
            this.opnameId = id;
            this.stokFisik = stok;
            this.showModal = true;
        }
    }
}
</script>
@endsection
