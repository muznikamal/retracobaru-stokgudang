<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'user']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_petugas', 'like', '%' . $request->search . '%')
                ->orWhereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $request->search . '%'))
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        // Filter petugas
        if ($request->filled('filter_petugas')) {
            $query->where('nama_petugas', $request->filter_petugas);
        }

        // Filter user
        if ($request->filled('filter_user')) {
            $query->whereHas('user', fn($q) => $q->where('name', $request->filter_user));
        }

        // Filter barang
        if ($request->filled('filter_barang')) {
            $query->whereHas('barang', fn($q) => $q->where('nama_barang', $request->filter_barang));
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        // Sort jumlah
        if ($request->sort == 'jumlah_asc') {
            $query->orderBy('jumlah', 'asc');
        } elseif ($request->sort == 'jumlah_desc') {
            $query->orderBy('jumlah', 'desc');
        } else {
            $query->latest();
        }

        $data = $query->paginate(10);

        // Untuk dropdown
        $namaPetugasList = BarangMasuk::select('nama_petugas')->distinct()->pluck('nama_petugas');
        $userList = \App\Models\User::select('name')->distinct()->pluck('name');
        $barangList = \App\Models\Barang::select('nama_barang')->orderBy('nama_barang')->pluck('nama_barang');

        return view('barang_masuk.index', compact('data', 'namaPetugasList', 'userList', 'barangList'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barang_masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'catatan' => 'nullable|array',
        ]);

        foreach ($request->barang_id as $index => $barangId) {
            
            $barangMasuk = \App\Models\BarangMasuk::create([
                'nama_petugas' => $request->nama_petugas,
                'barang_id' => $barangId,
                'jumlah' => $request->jumlah[$index],
                'user_id' => auth()->id(),
                'catatan' => $request->catatan[$index] ?? null,
            ]);

            // Update stok barang
            $barang = \App\Models\Barang::findOrFail($barangId);
            $barang->stok += $request->jumlah[$index];
            $barang->save();
        }

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Data barang masuk berhasil ditambahkan!');
    }


    public function show($id)
    {
        $barangMasuk = BarangMasuk::with('barang','user')->findOrFail($id);
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin|staff')) abort(403);
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barang = Barang::all();
        return view('barang_masuk.edit', compact('barangMasuk','barang'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin|staff')) abort(403);

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);

        // ✅ Kurangi stok lama dulu
        $oldBarang = Barang::findOrFail($barangMasuk->barang_id);
        $oldBarang->stok -= $barangMasuk->jumlah;
        $oldBarang->save();

        // ✅ Update data barang masuk
        $barangMasuk->update($request->only('barang_id','jumlah','catatan'));

        // ✅ Tambahkan stok baru
        $newBarang = Barang::findOrFail($request->barang_id);
        $newBarang->stok += $request->jumlah;
        $newBarang->save();

        return redirect()->route('barang-masuk.index')->with('success','Data barang masuk berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data ini.');
        }

        DB::beginTransaction(); // ✅ Pastikan operasi atomik (semua atau tidak sama sekali)

        try {
            $barangMasuk = BarangMasuk::findOrFail($id);
            $barang = Barang::findOrFail($barangMasuk->barang_id);

            // ✅ Cek apakah stok cukup untuk dikurangi
            if ($barang->stok < $barangMasuk->jumlah) {
                return redirect()->route('barang-masuk.index')->with('error', 'Stok saat ini tidak cukup untuk dikurangi.');
            }

            // ✅ Kurangi stok
            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();

            // ✅ Hapus data barang masuk
            $barangMasuk->delete();

            DB::commit();

            return redirect()->route('barang-masuk.index')
                ->with('success', 'Data barang masuk berhasil dihapus dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barang-masuk.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
