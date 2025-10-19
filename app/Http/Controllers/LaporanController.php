<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->endOfMonth()->toDateString();

        $data = Barang::with(['barangMasuk', 'barangKeluar'])->get()->map(function ($barang) use ($from, $to) {
            $totalMasuk = $barang->barangMasuk()
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $totalPenjualan = $barang->barangKeluar()
                ->where('tipe_keluar', 'penjualan')
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $totalKendala = $barang->barangKeluar()
                ->where('tipe_keluar', 'kendala')
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $totalKeluar = $totalPenjualan + $totalKendala;

            return [
                'nama_barang'     => $barang->nama_barang,
                'total_masuk'     => $totalMasuk,
                'total_penjualan' => $totalPenjualan,
                'total_kendala'   => $totalKendala,
                'total_keluar'    => $totalKeluar,
                'stok'            => $barang->stok,
            ];
        });

        // Hitung total keseluruhan
        $totalSummary = [
            'masuk'      => $data->sum('total_masuk'),
            'penjualan'  => $data->sum('total_penjualan'),
            'kendala'    => $data->sum('total_kendala'),
            'keluar'     => $data->sum('total_keluar'),
            'stok'       => $data->sum('stok'),
        ];

        return view('laporan.index', compact('data', 'from', 'to', 'totalSummary'));
    }



    public function exportExcel(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->endOfMonth()->toDateString();

        return Excel::download(new LaporanExport($from, $to), "laporan_barang_{$from}_{$to}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->endOfMonth()->toDateString();

        $data = Barang::with(['barangMasuk', 'barangKeluar'])->get()->map(function ($barang) use ($from, $to) {
            $totalMasuk = $barang->barangMasuk()
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $penjualan = $barang->barangKeluar()
                ->where('tipe_keluar', 'penjualan')
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $kendala = $barang->barangKeluar()
                ->where('tipe_keluar', 'kendala')
                ->whereBetween('created_at', [$from, $to])
                ->sum('jumlah');

            $totalKeluar = $penjualan + $kendala;

            return [
                'nama_barang' => $barang->nama_barang,
                'total_masuk' => $totalMasuk,
                'penjualan' => $penjualan,
                'kendala' => $kendala,
                'total_keluar' => $totalKeluar,
                'stok' => $barang->stok,
            ];
        });

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'from', 'to'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan_barang_{$from}_{$to}.pdf");
    }
}
