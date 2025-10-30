<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithStyles
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Barang::with(['barangMasuk', 'barangKeluar', 'opname'])
        ->orderBy('nama_barang', 'asc')
        ->get()
        ->map(function ($barang) {
            $totalMasuk = $barang->barangMasuk()
                ->whereBetween('created_at', [$this->from, $this->to])
                ->sum('jumlah');

            $penjualan = $barang->barangKeluar()
                ->where('tipe_keluar', 'penjualan')
                ->whereBetween('created_at', [$this->from, $this->to])
                ->sum('jumlah');

            $kendala = $barang->barangKeluar()
                ->where('tipe_keluar', 'kendala')
                ->whereBetween('created_at', [$this->from, $this->to])
                ->sum('jumlah');

            $totalKeluar = $penjualan + $kendala;

            // Ambil opname terakhir
            $selisihOpname = optional($barang->opname()->latest()->first())->selisih ?? 0;

            return [
                'nama_barang'     => $barang->nama_barang,
                'total_masuk'     => $totalMasuk,
                'penjualan'       => $penjualan,
                'kendala'         => $kendala,
                'total_keluar'    => $totalKeluar,
                'selisih_opname'  => $selisihOpname,
                'stok_akhir'      => $barang->stok,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Total Masuk',
            'Penjualan',
            'Kendala',
            'Total Keluar',
            'Selisih Opname',
            'Stok Akhir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType('solid')->getStartColor()->setARGB('16A34A');
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('A:G')->getAlignment()->setHorizontal('center');
        $sheet->getColumnDimension('A')->setWidth(25);
        return [];
    }
}
