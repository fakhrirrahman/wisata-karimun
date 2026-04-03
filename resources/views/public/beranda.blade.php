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

<!-- Ulasan Section -->
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Ulasan Pengunjung</h2>
                <p class="text-gray-600 mt-2">Bagikan pengalaman Anda setelah mengunjungi destinasi wisata di Karimun.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                    <p class="text-xs text-yellow-700">Rata-rata Rating</p>
                    <p class="text-lg font-bold text-yellow-800">{{ number_format($rataRating ?: 0, 1) }} / 5</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                    <p class="text-xs text-blue-700">Total Ulasan</p>
                    <p class="text-lg font-bold text-blue-800">{{ number_format($totalUlasan) }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @forelse($ulasanTerbaru as $ulasan)
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-5">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $ulasan->nama_pengulas }}</h3>
                                <p class="text-sm text-gray-500">Untuk {{ $ulasan->wisata->nama ?? 'Wisata' }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                                <i class="fas fa-star mr-1"></i>{{ $ulasan->rating }}/5
                            </span>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $ulasan->ulasan }}</p>
                        <p class="text-xs text-gray-400 mt-3">{{ $ulasan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-600">
                        Belum ada ulasan. Jadilah yang pertama memberi ulasan.
                    </div>
                @endforelse
            </div>

            <div class="bg-gray-50 rounded-xl border border-gray-100 p-6 h-fit">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kirim Ulasan</h3>
                <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="wisata_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Wisata</label>
                        <select id="wisata_id" name="wisata_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih destinasi --</option>
                            @foreach($wisata as $item)
                                <option value="{{ $item->id }}" {{ old('wisata_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_pengulas" class="block text-sm font-medium text-gray-700 mb-1">Nama Anda</label>
                        <input id="nama_pengulas" name="nama_pengulas" type="text" value="{{ old('nama_pengulas') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Budi" required>
                    </div>

                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <select id="rating" name="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih rating --</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} - {{ $i >= 4 ? 'Sangat Baik' : ($i == 3 ? 'Baik' : ($i == 2 ? 'Kurang' : 'Buruk')) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="ulasan" class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                        <textarea id="ulasan" name="ulasan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis pengalaman Anda..." required>{{ old('ulasan') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>
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
