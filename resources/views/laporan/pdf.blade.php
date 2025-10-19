<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            color: #16a34a;
        }
        .periode {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }
        table th {
            background-color: #16a34a;
            color: #fff;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .summary {
            margin-top: 20px;
            border-top: 2px solid #16a34a;
            padding-top: 10px;
        }
        .summary div {
            margin: 4px 0;
        }
        .summary strong {
            color: #16a34a;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN DATA BARANG</h2>
        <p class="periode">
            Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Total Masuk</th>
                <th>Penjualan</th>
                <th>Kendala</th>
                <th>Total Keluar</th>
                <th>Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td style="text-align:left">{{ $item['nama_barang'] }}</td>
                    <td>{{ $item['total_masuk'] }}</td>
                    <td>{{ $item['penjualan'] }}</td>
                    <td>{{ $item['kendala'] }}</td>
                    <td>{{ $item['total_keluar'] }}</td>
                    <td>{{ $item['stok'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
