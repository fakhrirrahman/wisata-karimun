@extends('layouts.admin')

@section('title', 'Statistik Kunjungan')
@section('page-title', 'Analisis Statistik Kunjungan')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('kunjungan.index') }}" class="text-blue-600 hover:text-blue-800 inline-block">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<!-- Statistik per Kategori -->
<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Statistik per Kategori</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($kategoriStats as $stat)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">{{ $stat->kategori }}</h4>
                        <p class="text-gray-600 text-sm mt-1">{{ $stat->jumlah_wisata }} wisata</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-blue-600">{{ $stat->total_visits }}</p>
                        <p class="text-gray-600 text-xs">total kunjungan</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @php
                    $maxVisits = $kategoriStats->max('total_visits');
                    $percentage = $maxVisits > 0 ? ($stat->total_visits / $maxVisits) * 100 : 0;
                @endphp
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>

                <p class="text-xs text-gray-600 mt-2">
                    Rata-rata: {{ $stat->jumlah_wisata > 0 ? round($stat->total_visits / $stat->jumlah_wisata) : 0 }} per wisata
                </p>
            </div>
        @empty
            <div class="col-span-2 text-center py-8 text-gray-500">
                <i class="fas fa-chart-pie text-4xl opacity-20 mb-2"></i>
                <p>Belum ada data statistik</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Top 10 Wisata Paling Dikunjungi -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">🔥 Top 10 Wisata Paling Dikunjungi</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Ranking</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama Wisata</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kategori</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-800">Jumlah Kunjungan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Visualisasi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($topWisata as $key => $wisata)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">
                            @if($key == 0)
                                <i class="fas fa-medal text-yellow-500 mr-2"></i>{{ $key + 1 }}
                            @elseif($key == 1)
                                <i class="fas fa-medal text-gray-400 mr-2"></i>{{ $key + 1 }}
                            @elseif($key == 2)
                                <i class="fas fa-medal text-orange-600 mr-2"></i>{{ $key + 1 }}
                            @else
                                <span class="text-gray-800">{{ $key + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $wisata->nama }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $wisata->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="font-bold text-lg text-gray-800">{{ $wisata->visits }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $maxVisits = $topWisata->max('visits');
                                $percentage = $maxVisits > 0 ? ($wisata->visits / $maxVisits) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl opacity-20 mb-4 block"></i>
                            Belum ada data wisata
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
