<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangOpname;
use Illuminate\Http\Request;

class BarangOpnameController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        $opnames = BarangOpname::with(['barang', 'user'])->latest()->paginate(10);

        return view('opname.index', compact('barangs', 'opnames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'stok_fisik' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $stok_sistem = $barang->stok;
        $stok_fisik  = $request->stok_fisik;
        $selisih     = $stok_fisik - $stok_sistem;

        // Simpan hasil opname
        BarangOpname::create([
            'barang_id'   => $barang->id,
            'stok_sistem' => $stok_sistem,
            'stok_fisik'  => $stok_fisik,
            'selisih'     => $selisih,
            'keterangan'  => $request->keterangan,
            'user_id'     => auth()->id(),
        ]);

        // ✅ Update stok barang sesuai hasil opname
        $barang->update(['stok' => $stok_fisik]);

        return redirect()->back()->with('success', 'Data opname berhasil disimpan dan stok barang diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_fisik' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $opname = BarangOpname::findOrFail($id);
        $barang = Barang::findOrFail($opname->barang_id);

        // Hitung ulang selisih baru
        $selisih_baru = $request->stok_fisik - $barang->stok;

        // Update data opname
        $opname->update([
            'stok_fisik' => $request->stok_fisik,
            'selisih'    => $selisih_baru,
            'keterangan' => $request->keterangan,
        ]);

        // ✅ Update stok barang agar sesuai dengan hasil opname baru
        $barang->update(['stok' => $request->stok_fisik]);

        return redirect()->route('opname.index')->with('success', 'Data opname dan stok barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $opname = BarangOpname::findOrFail($id);
        $barang = Barang::findOrFail($opname->barang_id);

        // ⚙️ (Opsional) Jika ingin stok barang dikembalikan ke stok sebelum opname
        $barang->update(['stok' => $opname->stok_sistem]);

        $opname->delete();

        return redirect()->route('opname.index')->with('success', 'Data opname berhasil dihapus.');
    }
}
