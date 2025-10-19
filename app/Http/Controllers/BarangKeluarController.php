<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        // --- Ambil semua request filter ---
        $search          = $request->search;
        $filterBarang    = $request->filter_barang;
        $filterPetugas   = $request->filter_petugas;
        $filterUser      = $request->filter_user;
        $filterTipe      = $request->filter_tipe;
        $tanggalMulai    = $request->tanggal_mulai;
        $tanggalSelesai  = $request->tanggal_selesai;
        $sort            = $request->sort;

        // --- Query dasar ---
        $query = BarangKeluar::with(['barang', 'user']);

        // 🔍 Filter pencarian umum
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_petugas', 'like', "%$search%")
                  ->orWhere('catatan', 'like', "%$search%")
                  ->orWhereHas('barang', function ($b) use ($search) {
                      $b->where('nama_barang', 'like', "%$search%");
                  })
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        // 📦 Filter barang
        if ($filterBarang) {
            $query->whereHas('barang', function ($b) use ($filterBarang) {
                $b->where('nama_barang', $filterBarang);
            });
        }

        // 👤 Filter petugas
        if ($filterPetugas) {
            $query->where('nama_petugas', $filterPetugas);
        }

        // 👨‍💻 Filter user input
        if ($filterUser) {
            $query->whereHas('user', function ($u) use ($filterUser) {
                $u->where('name', $filterUser);
            });
        }

        // 🏷️ Filter tipe keluar (penjualan/lainnya)
        if ($filterTipe) {
            $query->where('tipe_keluar', $filterTipe);
        }
        $tipeList = ['penjualan', 'kendala'];

        // 📅 Filter tanggal mulai & selesai
        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('created_at', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59']);
        } elseif ($tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $query->whereDate('created_at', '<=', $tanggalSelesai);
        }

        // 🔽 Sort jumlah dari kecil ke besar atau sebaliknya
        if ($sort == 'jumlah_asc') {
            $query->orderBy('jumlah', 'asc');
        } elseif ($sort == 'jumlah_desc') {
            $query->orderBy('jumlah', 'desc');
        } else {
            $query->latest();
        }

        // 🔢 Pagination
        $data = $query->paginate(10)->appends($request->query());

        // --- Data untuk dropdown filter ---
        $barangList      = Barang::orderBy('nama_barang')->pluck('nama_barang');
        $userList        = User::orderBy('name')->pluck('name');
        $namaPetugasList = BarangKeluar::select('nama_petugas')->distinct()->pluck('nama_petugas');

        return view('barang_keluar.index', compact(
            'data',
            'barangList',
            'userList',
            'namaPetugasList',
            'tipeList'
        ));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang', 'asc')->get();
        return view('barang_keluar.create', compact('barang'));
    }

    public function store(Request $request)
    {
        // ✅ Validasi array input
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'tipe_keluar' => 'required|array|min:1',
            'tipe_keluar.*' => 'required|in:penjualan,kendala',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string',
        ]);

        $namaPetugas = $request->nama_petugas;

        // ✅ Loop setiap barang yang diinput
        foreach ($request->barang_id as $index => $barangId) {
            $barang = Barang::findOrFail($barangId);
            $jumlah = $request->jumlah[$index];
            $tipeKeluar = $request->tipe_keluar[$index];
            $catatan = $request->catatan[$index] ?? null;

            // ✅ Validasi stok cukup
            if ($barang->stok < $jumlah) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "jumlah.$index" => "❌ Jumlah barang melebihi stok untuk {$barang->nama_barang} (Stok: {$barang->stok})"
                    ]);
            }

            // ✅ Simpan data barang keluar
            BarangKeluar::create([
                'nama_petugas' => $namaPetugas,
                'barang_id' => $barangId,
                'user_id' => auth()->id(),
                'jumlah' => $jumlah,
                'tipe_keluar' => $tipeKeluar,
                'catatan' => $catatan,
            ]);

            // ✅ Update stok barang
            $barang->stok -= $jumlah;
            $barang->save();
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Data barang keluar berhasil ditambahkan');
    }


    public function show($id)
    {
        $barangKeluar = BarangKeluar::with('barang','user')->findOrFail($id);
        return view('barang_keluar.show', compact('barangKeluar'));
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin|staff')) abort(403);

        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::all();

        return view('barang_keluar.edit', compact('barangKeluar','barang'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin|staff')) abort(403);

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tipe_keluar' => 'required|in:penjualan,kendala',
            'catatan' => 'nullable|string'
        ]);

        $barangKeluar = BarangKeluar::findOrFail($id);

        // ✅ Kembalikan stok lama
        $oldBarang = Barang::findOrFail($barangKeluar->barang_id);
        $oldBarang->stok += $barangKeluar->jumlah;
        $oldBarang->save();

        // ✅ Update data barang keluar
        $barangKeluar->update($request->only('barang_id','jumlah','tipe_keluar','catatan'));

        // ✅ Kurangi stok baru
        $newBarang = Barang::findOrFail($request->barang_id);

        if ($newBarang->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok barang tidak mencukupi untuk update!']);
        }

        $newBarang->stok -= $request->jumlah;
        $newBarang->save();

        return redirect()->route('barang-keluar.index')->with('success','Data barang keluar berhasil diperbarui');
    }

    

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data ini.');
        }

        DB::beginTransaction();

        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($barangKeluar->barang_id);

            // ✅ Kembalikan stok karena barang keluar dihapus
            $barang->stok += $barangKeluar->jumlah;
            $barang->save();

            // ✅ Hapus data barang keluar
            $barangKeluar->delete();

            DB::commit();

            return redirect()->route('barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil dihapus dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barang-keluar.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

}
