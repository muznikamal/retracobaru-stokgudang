@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="h-full px-6 py-4">
        <!-- Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Gudang</h1>

        <!-- Cards Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
            <!-- Total Barang -->
            <div
                class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold">Total Barang</h2>
                        <p class="text-3xl font-bold mt-2">{{ $totalBarang }}</p>
                    </div>
                </div>
            </div>
            <!-- Barang Masuk -->
            <div
                class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold">Barang Masuk (Bulan Ini)</h2>
                        <p class="text-3xl font-bold mt-2">{{ $barangMasukBulan }}</p>
                    </div>
                </div>
            </div>

            <!-- Barang Keluar -->
            <div
                class="bg-gradient-to-r from-pink-500 to-rose-600 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold">Barang Keluar (Bulan Ini)</h2>
                        <p class="text-3xl font-bold mt-2">{{ $barangKeluarBulan }}</p>
                    </div>
                </div>
            </div>
            <!-- Stok Menipis -->
            <div
                class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold">Stok Menipis</h2>
                        <p class="text-3xl font-bold mt-2">{{ $stokMenipis }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Grafik -->
        <div class="bg-white rounded-2xl shadow-md p-6 md:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">show_chart</span>
                    Grafik Barang Masuk & Keluar
                </h2>

                <!-- Toggle modern -->
                <div class="flex gap-2 bg-gray-100 rounded-xl p-1.5 shadow-inner flex-wrap justify-center">
                    <button data-mode="harian"
                        class="chart-btn active px-5 py-2.5 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-green-400 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        Per Hari
                    </button>
                    <button data-mode="bulanan"
                        class="chart-btn px-5 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-white hover:text-emerald-600 transition-all duration-200">
                        Per Bulan
                    </button>
                    <button data-mode="tahunan"
                        class="chart-btn px-5 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-white hover:text-emerald-600 transition-all duration-200">
                        Per Tahun
                    </button>
                </div>
            </div>

            <div class="relative">
                <canvas id="stockChart" class="w-full h-[320px]"></canvas>
            </div>
        </div>



        <!-- Tabel Ringkas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Barang Masuk Terbaru -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Barang Masuk Terbaru</h2>
                <table class="w-full text-left text-sm">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Barang</th>
                            <th class="py-2">Jumlah</th>
                            <th class="py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestMasuk as $m)
                            <tr class="border-b">
                                <td class="py-2">{{ $m->barang->nama_barang }}</td>
                                <td class="py-2">{{ $m->jumlah }}</td>
                                <td class="py-2">{{ $m->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Barang Keluar Terbaru -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Barang Keluar Terbaru</h2>
                <table class="w-full text-left text-sm">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Barang</th>
                            <th class="py-2">Jumlah</th>
                            <th class="py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestKeluar as $k)
                            <tr class="border-b">
                                <td class="py-2">{{ $k->barang->nama_barang }}</td>
                                <td class="py-2">{{ $k->jumlah }}</td>
                                <td class="py-2">{{ $k->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');

        // Dataset dari Controller
        const datasets = {
            harian: {
                labels: @json($hari),
                masuk: @json($dataMasukHarian),
                keluar: @json($dataKeluarHarian)
            },
            bulanan: {
                labels: @json($bulan),
                masuk: @json($dataMasuk),
                keluar: @json($dataKeluar)
            },
            tahunan: {
                labels: @json($tahun),
                masuk: @json($dataMasukTahunan),
                keluar: @json($dataKeluarTahunan)
            }
        };

        let mode = 'harian';
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: datasets[mode].labels,
                datasets: [{
                        label: 'Barang Masuk',
                        data: datasets[mode].masuk,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2,
                    },
                    {
                        label: 'Barang Keluar',
                        data: datasets[mode].keluar,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20,
                        bottom: 15,
                        left: 10,
                        right: 10
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            stepSize: 10,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        // Toggle
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.chart-btn').forEach(b => {
                    b.classList.remove('active');
                    b.classList.remove('bg-gradient-to-r', 'from-emerald-500', 'to-green-400',
                        'text-white', 'shadow-md');
                    b.classList.add('text-gray-700');
                });

                btn.classList.add('active', 'bg-gradient-to-r', 'from-emerald-500', 'to-green-400',
                    'text-white', 'shadow-md');
                btn.classList.remove('text-gray-700');

                mode = btn.dataset.mode;
                chart.data.labels = datasets[mode].labels;
                chart.data.datasets[0].data = datasets[mode].masuk;
                chart.data.datasets[1].data = datasets[mode].keluar;
                chart.update();
            });
        });
    </script>
    <style>
        /* Efek hover untuk tombol aktif */
        .chart-btn.active:hover {
            filter: brightness(1.05);
            transform: scale(1.02);
            transition: all 0.2s ease;
        }

        /* Efek hover normal untuk tombol non-aktif */
        .chart-btn:hover:not(.active) {
            background-color: white !important;
            color: #059669 !important;
            /* emerald-600 */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .chart-btn:focus {
            outline: none;
            ring: 2px solid #10b981;
        }
    </style>
@endpush
{{-- <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .icon-container {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
    }
</style> --}}
