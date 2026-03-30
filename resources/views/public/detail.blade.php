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
                        <div id="nearbyLoading" class="rounded-lg bg-blue-50 border border-blue-100 p-4 text-sm text-blue-700">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Mencari lokasi terdekat...
                        </div>

                        <div id="nearbyEmpty" class="hidden rounded-lg bg-gray-50 border border-gray-200 p-4 text-sm text-gray-600">
                            Belum ada data lokasi terdekat untuk ditampilkan saat ini.
                        </div>

                        <div id="nearbyQuickLinks" class="hidden rounded-lg bg-amber-50 border border-amber-100 p-4">
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

                        <div id="nearbyList" class="space-y-3"></div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wisataLat = {{ $wisata->latitude }};
            const wisataLng = {{ $wisata->longitude }};
            const nearbyListEl = document.getElementById('nearbyList');
            const nearbyLoadingEl = document.getElementById('nearbyLoading');
            const nearbyEmptyEl = document.getElementById('nearbyEmpty');
            const nearbyQuickLinksEl = document.getElementById('nearbyQuickLinks');
            const cacheKey = `nearby-${wisataLat.toFixed(4)}-${wisataLng.toFixed(4)}`;

            var map = L.map('map').setView([wisataLat, wisataLng], 14);
            const nearbyLayer = L.layerGroup().addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([wisataLat, wisataLng])
                .addTo(map)
                .bindPopup('<b>{{ $wisata->nama }}</b><br>{{ $wisata->kategori }}')
                .openPopup();

            const categoryConfig = {
                hotel: { label: 'Hotel', icon: 'fa-hotel', color: 'text-indigo-600' },
                restaurant: { label: 'Tempat Makan', icon: 'fa-utensils', color: 'text-orange-600' },
                cafe: { label: 'Kafe', icon: 'fa-mug-hot', color: 'text-amber-700' },
                service: { label: 'Layanan', icon: 'fa-store', color: 'text-green-600' },
                other: { label: 'Lainnya', icon: 'fa-location-dot', color: 'text-blue-600' }
            };

            function detectCategory(tags) {
                if (tags.tourism === 'hotel' || tags.tourism === 'guest_house' || tags.tourism === 'motel') return 'hotel';
                if (tags.amenity === 'restaurant' || tags.amenity === 'fast_food' || tags.amenity === 'food_court') return 'restaurant';
                if (tags.amenity === 'cafe') return 'cafe';
                if (['atm', 'bank', 'pharmacy', 'hospital', 'clinic', 'fuel', 'marketplace', 'supermarket'].includes(tags.amenity)) return 'service';
                return 'other';
            }

            function haversineKm(lat1, lon1, lat2, lon2) {
                const toRad = Math.PI / 180;
                const dLat = (lat2 - lat1) * toRad;
                const dLon = (lon2 - lon1) * toRad;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * toRad) * Math.cos(lat2 * toRad) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return 6371 * c;
            }

            function formatDistance(km) {
                if (km < 1) {
                    return `${Math.round(km * 1000)} m`;
                }
                return `${km.toFixed(2)} km`;
            }

            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function showEmptyState() {
                nearbyListEl.innerHTML = '';
                nearbyLayer.clearLayers();
                nearbyLoadingEl.classList.add('hidden');
                nearbyEmptyEl.classList.remove('hidden');
                nearbyQuickLinksEl.classList.remove('hidden');
            }

            function renderNearbyPlaces(places) {
                nearbyLoadingEl.classList.add('hidden');
                nearbyListEl.innerHTML = '';
                nearbyLayer.clearLayers();

                if (!places.length) {
                    showEmptyState();
                    return;
                }

                nearbyEmptyEl.classList.add('hidden');
                nearbyQuickLinksEl.classList.add('hidden');

                places.forEach((place) => {
                    const config = categoryConfig[place.category] || categoryConfig.other;
                    const item = document.createElement('div');
                    item.className = 'rounded-lg border border-gray-200 p-3';
                    item.innerHTML = `
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-800">${escapeHtml(place.name)}</p>
                                <p class="text-xs text-gray-500 mt-1">${escapeHtml(config.label)} · ${formatDistance(place.distanceKm)}</p>
                            </div>
                            <i class="fas ${config.icon} ${config.color}"></i>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query=${place.lat},${place.lng}" target="_blank" class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-700 font-semibold">
                            Lihat di Maps
                        </a>
                    `;
                    nearbyListEl.appendChild(item);

                    L.circleMarker([place.lat, place.lng], {
                        radius: 5,
                        color: '#2563eb',
                        fillColor: '#60a5fa',
                        fillOpacity: 0.8,
                        weight: 1
                    }).addTo(nearbyLayer)
                    .bindPopup(`<b>${escapeHtml(place.name)}</b><br>${escapeHtml(config.label)} - ${formatDistance(place.distanceKm)}`);
                });
            }

            function parsePlaces(elements) {
                const dedupe = new Set();

                return elements
                    .map((el) => {
                        const tags = el.tags || {};
                        const lat = el.lat || (el.center && el.center.lat);
                        const lng = el.lon || (el.center && el.center.lon);
                        const name = tags.name || tags.brand || tags.operator || `Lokasi ${detectCategory(tags)}`;

                        if (!lat || !lng || !name) return null;

                        const dedupeKey = `${name.toLowerCase()}-${lat.toFixed(5)}-${lng.toFixed(5)}`;
                        if (dedupe.has(dedupeKey)) return null;
                        dedupe.add(dedupeKey);

                        return {
                            name,
                            lat,
                            lng,
                            category: detectCategory(tags),
                            distanceKm: haversineKm(wisataLat, wisataLng, lat, lng)
                        };
                    })
                    .filter(Boolean)
                    .sort((a, b) => a.distanceKm - b.distanceKm)
                    .slice(0, 8);
            }

            function readCache() {
                try {
                    const raw = localStorage.getItem(cacheKey);
                    if (!raw) return null;
                    const parsed = JSON.parse(raw);
                    const maxAgeMs = 1000 * 60 * 60 * 6;
                    if (!parsed.time || !Array.isArray(parsed.places)) return null;
                    if (Date.now() - parsed.time > maxAgeMs) return null;
                    return parsed.places;
                } catch (_) {
                    return null;
                }
            }

            function writeCache(places) {
                try {
                    localStorage.setItem(cacheKey, JSON.stringify({
                        time: Date.now(),
                        places
                    }));
                } catch (_) {
                    // Ignore cache write errors in private mode or quota limits.
                }
            }

            function fetchWithTimeout(url, timeoutMs) {
                const controller = new AbortController();
                const timer = setTimeout(() => controller.abort(), timeoutMs);

                return fetch(url, { signal: controller.signal })
                    .finally(() => clearTimeout(timer));
            }

            function buildOverpassQuery(radius, includeAreas) {
                const wayAndRelation = includeAreas ? `
                    way(around:${radius},${wisataLat},${wisataLng})["tourism"~"hotel|guest_house|motel"];
                    way(around:${radius},${wisataLat},${wisataLng})["amenity"~"restaurant|cafe|fast_food|food_court|atm|bank|pharmacy|hospital|clinic|fuel|marketplace|supermarket"];
                    relation(around:${radius},${wisataLat},${wisataLng})["tourism"~"hotel|guest_house|motel"];
                    relation(around:${radius},${wisataLat},${wisataLng})["amenity"~"restaurant|cafe|fast_food|food_court|atm|bank|pharmacy|hospital|clinic|fuel|marketplace|supermarket"];
                ` : '';

                return `
                    [out:json][timeout:8];
                    (
                        node(around:${radius},${wisataLat},${wisataLng})["tourism"~"hotel|guest_house|motel"];
                        node(around:${radius},${wisataLat},${wisataLng})["amenity"~"restaurant|cafe|fast_food|food_court|atm|bank|pharmacy|hospital|clinic|fuel|marketplace|supermarket"];
                        ${wayAndRelation}
                    );
                    out center;
                `;
            }

            function requestOverpass(query) {
                const endpoints = [
                    'https://overpass-api.de/api/interpreter',
                    'https://overpass.kumi.systems/api/interpreter'
                ];

                const tryEndpoint = (index) => {
                    if (index >= endpoints.length) {
                        throw new Error('Semua endpoint Overpass gagal');
                    }

                    return fetchWithTimeout(`${endpoints[index]}?data=${encodeURIComponent(query)}`, 5000)
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}`);
                            }
                            return response.json();
                        })
                        .catch(() => tryEndpoint(index + 1));
                };

                return tryEndpoint(0);
            }

            function fetchNearbyPlaces() {
                const slowHintTimer = setTimeout(() => {
                    if (!nearbyLoadingEl.classList.contains('hidden')) {
                        nearbyQuickLinksEl.classList.remove('hidden');
                    }
                }, 2200);

                const fastQuery = buildOverpassQuery(3000, false);
                const fullQuery = buildOverpassQuery(7000, true);

                requestOverpass(fastQuery)
                    .then((fastData) => {
                        const fastPlaces = parsePlaces((fastData && fastData.elements) ? fastData.elements : []);

                        if (fastPlaces.length >= 4) {
                            writeCache(fastPlaces);
                            clearTimeout(slowHintTimer);
                            renderNearbyPlaces(fastPlaces);
                            return;
                        }

                        return requestOverpass(fullQuery)
                            .then((fullData) => {
                                const fullPlaces = parsePlaces((fullData && fullData.elements) ? fullData.elements : []);
                                const merged = [...fastPlaces, ...fullPlaces]
                                    .sort((a, b) => a.distanceKm - b.distanceKm)
                                    .filter((place, idx, arr) => idx === arr.findIndex((p) => p.name === place.name && Math.abs(p.lat - place.lat) < 0.00001 && Math.abs(p.lng - place.lng) < 0.00001))
                                    .slice(0, 8);

                                writeCache(merged);
                                clearTimeout(slowHintTimer);
                                renderNearbyPlaces(merged);
                            });
                    })
                    .catch(() => {
                        clearTimeout(slowHintTimer);
                        showEmptyState();
                    });
            }

            const cachedPlaces = readCache();
            if (cachedPlaces && cachedPlaces.length) {
                renderNearbyPlaces(cachedPlaces);
                fetchNearbyPlaces();
            } else {
                fetchNearbyPlaces();
            }
        });
    </script>
@endsection
