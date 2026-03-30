@extends('layouts.public')

@section('title', 'Beranda - Wisata Karimun')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl font-bold mb-4">Jelajahi Keindahan Karimun</h1>
                <p class="text-xl text-blue-100 mb-6">Temukan destinasi wisata terbaik di Kepulauan Karimun dengan panduan lengkap dan peta interaktif</p>
                <a href="{{ route('peta') }}" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                    <i class="fas fa-map mr-2"></i>Lihat Peta Wisata
                </a>
            </div>
            <div class="text-center">
                <i class="fas fa-map-location-dot text-8xl opacity-30"></i>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Tentang Karimun</h2>
        <div class="grid grid-cols-2 gap-8 items-center">
            <div>
                <p class="text-gray-700 text-lg leading-relaxed mb-4">
                    Karimun adalah kepulauan yang kaya akan keindahan alam dan budaya lokal. Dengan berbagai destinasi wisata yang menarik, dari pantai eksotis hingga situs budaya bersejarah, Karimun menawarkan pengalaman wisata yang tak terlupakan.
                </p>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Sistem informasi geografis ini dirancang untuk membantu Anda menemukan dan menjelajahi semua destinasi wisata yang tersedia di Karimun dengan mudah dan interaktif.
                </p>
            </div>
            <div class="bg-blue-50 rounded-lg p-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $wisata->count() }}</p>
                        <p class="text-gray-600">Destinasi Wisata</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">5</p>
                        <p class="text-gray-600">Kecamatan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-purple-600">5</p>
                        <p class="text-gray-600">Kategori Wisata</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-orange-600">24/7</p>
                        <p class="text-gray-600">Akses Online</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Kategori Wisata</h2>
        <div class="grid grid-cols-5 gap-4">
            @php
                $kategori = ['Wisata Alam', 'Wisata Budaya', 'Wisata Buatan', 'Wisata Kuliner', 'Wisata Kerajinan'];
                $icons = ['fa-leaf', 'fa-landmark', 'fa-building', 'fa-utensils', 'fa-hammer'];
                $colors = ['green', 'blue', 'purple', 'orange', 'red'];
            @endphp
            @foreach($kategori as $key => $kat)
                <div class="bg-white rounded-lg p-6 text-center hover:shadow-lg transition">
                    <i class="fas {{ $icons[$key] }} text-4xl text-{{ $colors[$key] }}-500 mb-4 block"></i>
                    <h3 class="font-semibold text-gray-800">{{ $kat }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Most View Section -->
<section class="py-16 bg-gray-50 border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Most View Wisata</h2>
                <p class="text-gray-600 mt-2">Berdasarkan jumlah klik detail wisata yang tercatat pada sistem admin.</p>
            </div>
            <span class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold w-fit">
                <i class="fas fa-chart-line mr-2"></i>Update otomatis dari data kunjungan
            </span>
        </div>

        @php
            $topVisited = $mostViewedWisata->filter(fn($item) => (int) $item->visits > 0);
        @endphp

        @if($topVisited->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Belum ada data kunjungan untuk ditampilkan sebagai Most View.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topVisited as $rank => $item)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition group">
                        <div class="relative h-44 overflow-hidden bg-gray-200 flex items-center justify-center">
                            @if($item->gambar)
                                <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            @endif
                            <div class="absolute top-3 left-3 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-md">
                                #{{ $rank + 1 }}
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->nama }}</h3>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded whitespace-nowrap">
                                    {{ $item->kategori }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $item->alamat }}
                            </p>

                            <div class="flex items-center justify-between border-t pt-4">
                                <span class="text-sm font-semibold text-gray-700">
                                    <i class="fas fa-eye text-blue-600 mr-2"></i>{{ number_format($item->visits) }} kali dilihat
                                </span>
                                <a href="{{ route('detail', $item->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Wisata List Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-12 text-gray-800">Daftar Wisata</h2>
        
        @if($wisata->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-500 text-xl">Belum ada data wisata tersedia</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wisata as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden bg-gray-300 flex items-center justify-center">
                            @if($item->gambar)
                                <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 flex-1">{{ $item->nama }}</h3>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                    {{ $item->kategori }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $item->alamat }}
                            </p>
                            
                            <div class="border-t pt-4">
                                <a href="{{ route('detail', $item->id) }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition font-semibold">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Fitur Unggulan</h2>
        <div class="grid grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-500 text-white w-16 h-16 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-map"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Peta Interaktif</h3>
                <p class="text-gray-600">Jelajahi destinasi wisata dengan peta interaktif yang mudah digunakan</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-filter"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Filter Pencarian</h3>
                <p class="text-gray-600">Cari wisata berdasarkan kategori, lokasi, atau nama dengan mudah</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-500 text-white w-16 h-16 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-route"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Panduan Rute</h3>
                <p class="text-gray-600">Dapatkan panduan rute menuju destinasi pilihan Anda</p>
            </div>
        </div>
    </div>
</section>
@endsection
