@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Total Wisata</p>
        <p class="text-2xl font-bold text-gray-800">{{ $wisataData->count() }}</p>
        <i class="fas fa-map-marker-alt text-blue-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Kategori</p>
        <p class="text-2xl font-bold text-gray-800">{{ $kategoriCount->count() }}</p>
        <i class="fas fa-th text-green-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Kecamatan</p>
        <p class="text-2xl font-bold text-gray-800">{{ $kecamatanCount->count() }}</p>
        <i class="fas fa-city text-purple-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Total Harga</p>
        <p class="text-xl font-bold text-gray-800">
            @php
                $totalHarga = $wisataData->sum('harga');
                echo 'Rp ' . number_format($totalHarga, 0, ',', '.');
            @endphp
        </p>
    </div>
</div>

<!-- Chart Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    <!-- Chart Wisata Per Tahun -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Kunjungan Wisata Per Tahun
            </h3>
        </div>
        <div style="height: 300px; max-height: 300px;">
            <canvas id="wisataPerTahunChart"></canvas>
        </div>
    </div>

    <!-- Chart Wisata Per Kategori -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-green-500 mr-2"></i>
            Distribusi Kategori Wisata
        </h3>
        <div style="height: 300px; max-height: 300px;">
            <canvas id="kategoriChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart Wisata Per Bulan -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
            Kunjungan Wisata Per Bulan
        </h3>
        <div class="flex items-center space-x-2">
            <label for="tahunFilter" class="text-sm text-gray-600 font-medium">Pilih Tahun:</label>
            <select id="tahunFilter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($tahunTersedia as $tahun)
                    <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div style="height: 350px; max-height: 350px;">
        <canvas id="wisataPerBulanChart"></canvas>
    </div>
</div>

<!-- Tabel Kunjungan Per Wisata -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
        Top 10 Wisata Dengan Kunjungan Terbanyak
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Wisata</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Jumlah Kunjungan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kunjunganPerWisata as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->wisata->nama ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $item->wisata->kategori ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                            {{ $item->wisata->alamat ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                <i class="fas fa-eye mr-2"></i>
                                {{ number_format($item->total_kunjungan) }} kunjungan
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2 block"></i>
                            Belum ada data kunjungan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Wisata Per Tahun
    const wisataPerTahunCtx = document.getElementById('wisataPerTahunChart').getContext('2d');
    const wisataDetailPerTahun = {!! json_encode($wisataDetailPerTahun) !!};
    
    const wisataPerTahunChart = new Chart(wisataPerTahunCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($wisataPerTahun->pluck('tahun')) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode($wisataPerTahun->pluck('total')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    padding: 15,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    bodySpacing: 6,
                    callbacks: {
                        title: function(context) {
                            return 'Tahun ' + context[0].label;
                        },
                        label: function(context) {
                            return 'Total Kunjungan: ' + context.parsed.y.toLocaleString();
                        },
                        afterLabel: function(context) {
                            const tahun = context.label;
                            const details = wisataDetailPerTahun[tahun];
                            
                            if (details && details.length > 0) {
                                let lines = ['\nTop 5 Wisata:'];
                                details.forEach((item, index) => {
                                    lines.push(`${index + 1}. ${item.nama} (${item.jumlah}x)`);
                                });
                                return lines;
                            }
                            return [];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart Kategori
    const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
    const kategoriChart = new Chart(kategoriCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriCount->pluck('kategori')) !!},
            datasets: [{
                data: {!! json_encode($kategoriCount->pluck('total')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Chart Wisata Per Bulan
    const wisataPerBulanCtx = document.getElementById('wisataPerBulanChart').getContext('2d');
    let wisataPerBulanChart = null;

    // Function untuk load data per bulan
    function loadWisataPerBulan(tahun) {
        fetch(`/dashboard/wisata-per-bulan?tahun=${tahun}`)
            .then(response => response.json())
            .then(result => {
                const data = result.data;
                const details = result.details;
                
                const labels = data.map(item => item.bulan);
                const totals = data.map(item => item.total);
                const bulanNumbers = data.map(item => item.bulan_number);

                // Destroy chart lama jika ada
                if (wisataPerBulanChart) {
                    wisataPerBulanChart.destroy();
                }

                // Buat chart baru
                wisataPerBulanChart = new Chart(wisataPerBulanCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Kunjungan',
                            data: totals,
                            backgroundColor: 'rgba(139, 92, 246, 0.2)',
                            borderColor: 'rgba(139, 92, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                padding: 15,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                bodySpacing: 6,
                                callbacks: {
                                    title: function(context) {
                                        return context[0].label + ' ' + tahun;
                                    },
                                    label: function(context) {
                                        return 'Total Kunjungan: ' + context.parsed.y.toLocaleString();
                                    },
                                    afterLabel: function(context) {
                                        const bulanNumber = bulanNumbers[context.dataIndex];
                                        const wisataDetails = details[bulanNumber];
                                        
                                        if (wisataDetails && wisataDetails.length > 0) {
                                            let lines = ['\nTop 5 Wisata:'];
                                            wisataDetails.forEach((item, index) => {
                                                lines.push(`${index + 1}. ${item.nama} (${item.jumlah}x)`);
                                            });
                                            return lines;
                                        }
                                        return [];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
    }

    // Load data tahun saat ini saat halaman pertama kali dimuat
    const currentYear = document.getElementById('tahunFilter').value;
    loadWisataPerBulan(currentYear);

    // Event listener untuk filter tahun
    document.getElementById('tahunFilter').addEventListener('change', function() {
        loadWisataPerBulan(this.value);
    });
</script>
@endpush