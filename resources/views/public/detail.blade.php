@extends('layouts.public')

@section('title', $wisata->nama . ' - Wisata Karimun')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 mb-8 text-gray-600">
            <a href="{{ route('beranda') }}" class="hover:text-blue-600">Beranda</a>
            <i class="fas fa-chevron-right text-sm"></i>
            <span>{{ $wisata->nama }}</span>
        </div>

        <div class="grid grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="col-span-2">
                <!-- Gambar -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                    <div class="h-96 bg-gray-200 flex items-center justify-center">
                        @if ($wisata->gambar)
                            <img src="{{ Storage::url($wisata->gambar) }}"
                            alt="{{ $wisata->nama }}"
                            class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-400 mb-4"></i>
                                <p class="text-gray-400">Gambar tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $wisata->nama }}</h1>

                    <div class="flex items-center space-x-4 mb-6">
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold">
                            {{ $wisata->kategori }}
                        </span>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                            <span>{{ $wisata->alamat }}</span>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Deskripsi -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $wisata->deskripsi }}</p>
                    </div>

                    <!-- Info Penting -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6">
                            <p class="text-gray-600 text-sm uppercase font-semibold mb-2">Harga Tiket</p>
                            <p class="text-2xl font-bold text-blue-600">
                                @if ($wisata->harga)
                                    Rp{{ number_format($wisata->harga, 0, ',', '.') }}
                                @else
                                    <span class="text-green-600">Gratis</span>
                                @endif
                            </p>
                        </div>

                        <div class="bg-green-50 rounded-lg p-6">
                            <p class="text-gray-600 text-sm uppercase font-semibold mb-2">Koordinat</p>
                            <p class="text-sm text-gray-800">
                                <span class="font-semibold">Lat:</span> {{ $wisata->latitude }}<br>
                                <span class="font-semibold">Long:</span> {{ $wisata->longitude }}
                            </p>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    @if ($wisata->fasilitas)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Fasilitas</h2>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $fasilitasList = is_array($wisata->fasilitas)
                                        ? $wisata->fasilitas
                                        : json_decode($wisata->fasilitas, true) ?? [];
                                @endphp
                                @forelse($fasilitasList as $fasilitas)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span class="text-gray-700">{{ $fasilitas }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 col-span-2">Tidak ada fasilitas yang tersedia</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Ulasan Pengunjung -->
                <div id="ulasan" class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Ulasan Pengunjung</h2>
                            <p class="text-gray-600 mt-1">Bagikan pengalaman Anda setelah berkunjung ke {{ $wisata->nama }}.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                                <p class="text-xs text-yellow-700">Rata-rata Rating</p>
                                <p class="text-lg font-bold text-yellow-800">{{ number_format($rataRatingWisata ?: 0, 1) }} / 5</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                                <p class="text-xs text-blue-700">Total Ulasan</p>
                                <p class="text-lg font-bold text-blue-800">{{ number_format($totalUlasanWisata) }}</p>
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

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2 space-y-4">
                            @forelse($ulasanWisata as $ulasan)
                                <div class="bg-gray-50 rounded-xl border border-gray-100 p-5">
                                    <div class="flex items-start justify-between gap-4 mb-2">
                                        <h3 class="font-semibold text-gray-900">{{ $ulasan->nama_pengulas }}</h3>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                                            <i class="fas fa-star mr-1"></i>{{ $ulasan->rating }}/5
                                        </span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $ulasan->ulasan }}</p>
                                    <p class="text-xs text-gray-400 mt-3">{{ $ulasan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-600">
                                    Belum ada ulasan untuk wisata ini. Jadilah yang pertama.
                                </div>
                            @endforelse

                            @if($ulasanWisata->hasPages())
                                <div class="pt-2">
                                    {{ $ulasanWisata->onEachSide(1)->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-5 h-fit">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tulis Ulasan</h3>
                            <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="wisata_id" value="{{ $wisata->id }}">

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

                <!-- Map -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lokasi di Peta</h2>
                    <div id="map" class="h-96 rounded-lg overflow-hidden"></div>
                </div>

                <!-- Rute -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-4">Panduan Menuju Lokasi</h2>
                    <p class="mb-6">Dapatkan panduan rute lengkap melalui Google Maps</p>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $wisata->latitude }},{{ $wisata->longitude }}"
                        target="_blank"
                        class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-directions mr-2"></i>Buka di Google Maps
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-span-1">
                <!-- Nearby Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Lokasi Terdekat</h3>
                    <p class="text-sm text-gray-600 mb-6">Hotel, tempat makan, dan layanan sekitar lokasi wisata ini.</p>

                    <div class="space-y-4">
                        @php
                            $nearbyStyleMap = [
                                'hotel' => ['icon' => 'fa-hotel', 'color' => 'text-indigo-600', 'label' => 'Hotel'],
                                'restaurant' => ['icon' => 'fa-utensils', 'color' => 'text-orange-600', 'label' => 'Tempat Makan'],
                                'service' => ['icon' => 'fa-store', 'color' => 'text-green-600', 'label' => 'Layanan'],
                                'other' => ['icon' => 'fa-location-dot', 'color' => 'text-blue-600', 'label' => 'Lainnya'],
                            ];
                        @endphp

                        @forelse($nearbyPlaces as $place)
                            @php
                                $style = $nearbyStyleMap[$place->kategori] ?? $nearbyStyleMap['other'];
                            @endphp
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $place->nama }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $style['label'] }}
                                            @if(!is_null($place->distance_km))
                                                ·
                                                @if($place->distance_km < 1)
                                                    {{ round($place->distance_km * 1000) }} m
                                                @else
                                                    {{ number_format($place->distance_km, 2) }} km
                                                @endif
                                            @endif
                                        </p>
                                        @if($place->alamat)
                                            <p class="text-xs text-gray-500 mt-1">{{ $place->alamat }}</p>
                                        @endif
                                    </div>
                                    <i class="fas {{ $style['icon'] }} {{ $style['color'] }}"></i>
                                </div>
                                @if($place->latitude && $place->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $place->latitude }},{{ $place->longitude }}" target="_blank" class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                        Lihat di Maps
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-lg bg-gray-50 border border-gray-200 p-4 text-sm text-gray-600">
                                Belum ada data lokasi terdekat untuk ditampilkan saat ini.
                            </div>
                            <div class="rounded-lg bg-amber-50 border border-amber-100 p-4">
                                <p class="text-sm font-semibold text-amber-800 mb-3">Cek cepat via Google Maps</p>
                                <div class="grid grid-cols-1 gap-2 text-sm">
                                    <a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/hotel/@{{ $wisata->latitude }},{{ $wisata->longitude }},15z?entry=ttu" class="text-blue-700 hover:text-blue-800 font-semibold">
                                        <i class="fas fa-hotel mr-2"></i>Cari hotel terdekat
                                    </a>
                                    <a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/restoran/@{{ $wisata->latitude }},{{ $wisata->longitude }},15z?entry=ttu" class="text-blue-700 hover:text-blue-800 font-semibold">
                                        <i class="fas fa-utensils mr-2"></i>Cari tempat makan terdekat
                                    </a>
                                    <a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/atm+bank+apotek/@{{ $wisata->latitude }},{{ $wisata->longitude }},15z?entry=ttu" class="text-blue-700 hover:text-blue-800 font-semibold">
                                        <i class="fas fa-store mr-2"></i>Cari layanan terdekat
                                    </a>
                                </div>
                            </div>
                        @endforelse

                        <hr>

                        <a href="{{ route('peta') }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition font-semibold">
                            <i class="fas fa-map mr-2"></i>Kembali ke Peta
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @php
        $nearbyPlacesJson = $nearbyPlaces
            ->map(function ($place) {
                return [
                    'nama' => $place->nama,
                    'kategoriLabel' => $place->kategori_label,
                    'latitude' => $place->latitude,
                    'longitude' => $place->longitude,
                ];
            })
            ->values()
            ->toJson();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wisataLat = {{ $wisata->latitude }};
            const wisataLng = {{ $wisata->longitude }};
            const nearbyPlaces = {!! $nearbyPlacesJson !!};

            var map = L.map('map').setView([wisataLat, wisataLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([wisataLat, wisataLng])
                .addTo(map)
                .bindPopup('<b>{{ $wisata->nama }}</b><br>{{ $wisata->kategori }}')
                .openPopup();

            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            nearbyPlaces.forEach((place) => {
                const lat = parseFloat(place.latitude);
                const lng = parseFloat(place.longitude);

                if (Number.isNaN(lat) || Number.isNaN(lng)) {
                    return;
                }

                L.circleMarker([lat, lng], {
                    radius: 5,
                    color: '#2563eb',
                    fillColor: '#60a5fa',
                    fillOpacity: 0.8,
                    weight: 1
                }).addTo(map)
                .bindPopup(`<b>${escapeHtml(place.nama)}</b><br>${escapeHtml(place.kategoriLabel)}`);
            });
        });
    </script>
@endsection
