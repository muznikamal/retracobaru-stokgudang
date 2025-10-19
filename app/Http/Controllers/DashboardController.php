<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan data
        $totalBarang = Barang::count();
        $barangMasukBulan = BarangMasuk::whereMonth('created_at', now()->month)->sum('jumlah');
        $barangKeluarBulan = BarangKeluar::whereMonth('created_at', now()->month)->sum('jumlah');
        $stokMenipis = Barang::where('stok', '<', 5)->count();

        // Data tabel
        $latestMasuk = BarangMasuk::with('barang')->latest()->take(5)->get();
        $latestKeluar = BarangKeluar::with('barang')->latest()->take(5)->get();

        // === GRAFIK ===

        // 📅 Grafik bulanan
        $bulan = collect(range(1, 12))->map(fn($m) => date('M', mktime(0, 0, 0, $m, 1)));
        $dataMasuk = collect(range(1, 12))->map(fn($m) => BarangMasuk::whereMonth('created_at', $m)->sum('jumlah'));
        $dataKeluar = collect(range(1, 12))->map(fn($m) => BarangKeluar::whereMonth('created_at', $m)->sum('jumlah'));

        // 📆 Grafik harian (minggu ini)
        $hari = collect(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']);
        $dataMasukHarian = collect(range(0, 6))->map(fn($i) =>
            BarangMasuk::whereDate('created_at', now()->startOfWeek()->addDays($i))->sum('jumlah')
        );
        $dataKeluarHarian = collect(range(0, 6))->map(fn($i) =>
            BarangKeluar::whereDate('created_at', now()->startOfWeek()->addDays($i))->sum('jumlah')
        );

        // 📊 Grafik tahunan (3 tahun terakhir)
        $tahun = collect(range(now()->year - 2, now()->year));
        $dataMasukTahunan = $tahun->map(fn($y) => BarangMasuk::whereYear('created_at', $y)->sum('jumlah'));
        $dataKeluarTahunan = $tahun->map(fn($y) => BarangKeluar::whereYear('created_at', $y)->sum('jumlah'));

        return view('dashboard', compact(
            'totalBarang',
            'barangMasukBulan',
            'barangKeluarBulan',
            'stokMenipis',
            'latestMasuk',
            'latestKeluar',
            'bulan', 'dataMasuk', 'dataKeluar',
            'hari', 'dataMasukHarian', 'dataKeluarHarian',
            'tahun', 'dataMasukTahunan', 'dataKeluarTahunan'
        ));
    }

}
