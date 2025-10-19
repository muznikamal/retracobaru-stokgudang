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
        @foreach ($data as $item)
        <tr>
            <td>{{ $item['nama_barang'] }}</td>
            <td>{{ $item['total_masuk'] }}</td>
            <td>{{ $item['penjualan'] }}</td>
            <td>{{ $item['kendala'] }}</td>
            <td>{{ $item['total_keluar'] }}</td>
            <td>{{ $item['stok'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
