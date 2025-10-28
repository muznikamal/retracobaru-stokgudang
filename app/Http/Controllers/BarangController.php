<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        // List kategori untuk dropdown
        $kategoriList = Barang::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // 🎯 Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 🔽 Sort berdasarkan header
        switch ($request->sort) {
            case 'nama_asc':
                $query->orderBy('nama_barang', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_barang', 'desc');
                break;
            case 'stok_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stok_desc':
                $query->orderBy('stok', 'desc');
                break;
            default:
                $query->latest();
        }

        $barang = $query->paginate(20)->appends($request->query());

        return view('barang.index', compact('barang', 'kategoriList'));
    }


    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'nullable|string|max:50',
            'kategori'    => 'nullable|string|max:100',
            'nama_petugas' => 'nullable|string|max:255',
        ]);

        $satuan = $request->satuan === 'lainnya' 
            ? $request->satuan_manual 
            : $request->satuan;

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok,
            'satuan'      => $satuan,
            'kategori'    => $request->kategori,
        ]);

        // ✅ Tambahkan ke log barang masuk
        BarangMasuk::create([
            'barang_id' => $barang->id,
            'user_id' => auth()->id(),
            'nama_petugas' => $request->nama_petugas,
            'jumlah' => $request->stok,
            'catatan' => 'Stok barang baru ditambahkan oleh admin',
            
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan dan tercatat ke Barang Masuk');
    }


    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'nullable|string|max:50',
            'kategori'    => 'nullable|string|max:100',
        ]);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'kategori'    => $request->kategori,
        ]);

        return redirect()->route('barang.index')->with('success','Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success','Barang berhasil dihapus');
    }

    public function __construct()
    {
        $this->middleware('can:barang.create')->only(['create', 'store']);
        $this->middleware('can:barang.edit')->only(['edit', 'update']);
        $this->middleware('can:barang.delete')->only(['destroy']);
    }

}
