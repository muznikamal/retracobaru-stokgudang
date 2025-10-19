<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView, WithTitle
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $from = $this->from;
        $to   = $this->to;

        $data = Barang::with(['barangMasuk', 'barangKeluar'])->get()->map(function ($barang) use ($from, $to) {
            $totalMasuk = $barang->barangMasuk()->whereBetween('created_at', [$from, $to])->sum('jumlah');
            $penjualan  = $barang->barangKeluar()->where('tipe_keluar', 'penjualan')->whereBetween('created_at', [$from, $to])->sum('jumlah');
            $kendala    = $barang->barangKeluar()->where('tipe_keluar', 'kendala')->whereBetween('created_at', [$from, $to])->sum('jumlah');
            $totalKeluar = $penjualan + $kendala;

            return [
                'nama_barang' => $barang->nama_barang,
                'total_masuk' => $totalMasuk,
                'penjualan'   => $penjualan,
                'kendala'     => $kendala,
                'total_keluar'=> $totalKeluar,
                'stok'        => $barang->stok,
            ];
        });

        return view('laporan.excel', compact('data', 'from', 'to'));
    }

    public function title(): string
    {
        return 'Laporan Barang';
    }
}
