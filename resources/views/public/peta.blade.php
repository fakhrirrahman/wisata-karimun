@extends('layouts.public')

@section('title', 'Peta Wisata - Karimun')

@section('content')
    <div class="h-screen flex">
        <!-- Sidebar Filters -->
        <aside class="w-80 bg-white shadow-lg overflow-y-auto">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Filter Pencarian</h2>
                <p class="text-gray-600 text-sm">Temukan wisata favorit Anda</p>
            </div>

            <!-- Search -->
            <div class="p-6 border-b">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Nama Wisata</label>
                <input type="text" id="searchInput"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none"
                    placeholder="Nama wisata...">
            </div>

            <!-- Mode Map -->
            <div class="p-6 border-b">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Mode Tampilan Peta</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="mapMode" value="normal" checked class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">Peta Normal (Batas Kecamatan)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="mapMode" value="heatmap" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">Heatmap Kunjungan Wisata</span>
                    </label>
                </div>
            </div>

            <!-- Kategori Filter -->
            <div class="p-6 border-b">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Kategori</label>
                <div class="space-y-2 radio-group">
                    <label class="flex items-center">
                        <input type="radio" name="category" value="all" checked class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">Semua Kategori</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="category" value="Wisata Alam" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">🌿 Wisata Alam</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="category" value="Wisata Budaya" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">🏛️ Wisata Budaya</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="category" value="Wisata Buatan" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">🎢 Wisata Buatan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="category" value="Wisata Kuliner" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">🍽️ Wisata Kuliner</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="category" value="Wisata Kerajinan" class="h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">🎨 Wisata Kerajinan</span>
                    </label>
                </div>
            </div>

            <!-- Kecamatan Filter -->
            <div class="p-6 border-b">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Kecamatan</label>
                <div class="space-y-2">
                    @php
                        // Harus sesuai dengan NAMOBJ yang ada di GeoJSON
                        $kecamatanList = [
                            'BURU',
                            'DURAI',
                            'KARIMUN',
                            'KUNDUR',
                            'KUNDUR BARAT',
                            'MERAL',
                            'MORO',
                            'TEBING',
                        ];
                    @endphp
                    <label class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="all" checked>
                        <span class="ml-2 text-gray-700">Semua Kecamatan</span>
                    </label>
                    @foreach ($kecamatanList as $kec)
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox"
                                value="{{ $kec }}">
                            <span class="ml-2 text-gray-700">{{ $kec }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Hasil Pencarian / Daftar Wisata -->
            <div class="p-6 border-t bg-gray-50 max-h-80 overflow-y-auto">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    Hasil Pencarian
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full ml-2"
                        id="wisataCount">
                        {{ count($wisata) }}
                    </span>
                </h3>
                <div id="wisataList" class="space-y-2">
                    @forelse($wisata as $item)
                        <div class="wisata-item p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition bg-white wisata-{{ strtolower(str_replace(' ', '-', $item->kategori ?? '')) }} wisata-{{ strtolower(str_replace(' ', '-', $item->kecamatan ?? '')) }}"
                            data-wisata-id="{{ $item->id }}" data-wisata-category="{{ $item->kategori }}"
                            data-wisata-kecamatan="{{ $item->kecamatan }}">
                            <p class="text-sm font-semibold text-gray-800">{{ $item->nama }}</p>
                            <p class="text-xs text-gray-500 mt-1">📍 {{ $item->kecamatan ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">
                                    {{ $item->kategori }}
                                </span>
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">Tidak ada data wisata</p>
                    @endforelse
                </div>
            </div>

            <!-- Legend -->
            <div class="p-6 bg-gray-50 border-t">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Legenda Peta</h3>

                <!-- Kecamatan Legend -->
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-600 mb-3 uppercase">Kecamatan</p>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #FF6B6B;"></div>
                            <span>BURU</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #4ECDC4;"></div>
                            <span>DURAI</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #45B7D1;"></div>
                            <span>KARIMUN</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #FFA07A;"></div>
                            <span>KUNDUR</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #98D8C8;"></div>
                            <span>KUNDUR BARAT</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #F7DC6F;"></div>
                            <span>MERAL</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #BB8FCE;"></div>
                            <span>MORO</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 rounded mr-2" style="background-color: #85C1E2;"></div>
                            <span>TEBING</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div id="normalLegend">
                    <p class="text-xs font-semibold text-gray-600 mb-3 uppercase">Kategori Wisata (Marker)</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                            <span>Wisata Alam</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                            <span>Wisata Budaya</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                            <span>Wisata Buatan</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                            <span>Wisata Kuliner</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-purple-500 mr-2"></div>
                            <span>Wisata Kerajinan</span>
                        </div>
                    </div>
                </div>

                <div id="heatLegend" class="hidden">
                    <p class="text-xs font-semibold text-gray-600 mb-3 uppercase">Heatmap Kunjungan (Terang ke Gelap)</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <div class="w-5 h-3 rounded mr-2" style="background-color: #FFF5EB;"></div>
                            <span>Tidak Pernah (0)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-3 rounded mr-2" style="background-color: #FEE6CE;"></div>
                            <span>Jarang (1-10)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-3 rounded mr-2" style="background-color: #FDAE6B;"></div>
                            <span>Normal (11-50)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-3 rounded mr-2" style="background-color: #E6550D;"></div>
                            <span>Sering (51-150)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-3 rounded mr-2" style="background-color: #7F2704;"></div>
                            <span>Sangat Sering (&gt;150)</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Map -->
        <div class="flex-1 relative">
            <div id="map" class="w-full h-full"></div>
            <a href="{{ route('beranda') }}"
                class="absolute top-6 right-6 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition">
                <i class="fas fa-home mr-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <style>
        .wisata-popup .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .wisata-popup .leaflet-popup-content {
            margin: 0;
            width: 300px !important;
        }

        .wisata-popup .leaflet-popup-tip {
            background: #ffffff;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ================== MAP INIT ================== */
            const map = L.map('map');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function coordsToLatLng(coords) {
                return L.latLng(coords[1], coords[0]); // buang Z
            }

            /* ================== STATE ================== */
            const wisataLayerGroup = L.layerGroup().addTo(map);
            let kecamatanGeoJsonLayer = null;
            let markerMap = {};
            let visitsPerKecamatan = {};
            const wisataData = {!! json_encode($wisata) !!};
            let currentMapMode = 'normal';
            const normalLegendEl = document.getElementById('normalLegend');
            const heatLegendEl = document.getElementById('heatLegend');
            const detailBaseUrl = @json(url('/detail'));

            const categoryColor = {
                'Wisata Alam': '#22c55e',
                'Wisata Budaya': '#ef4444',
                'Wisata Buatan': '#3b82f6',
                'Wisata Kuliner': '#f59e0b',
                'Wisata Kerajinan': '#a855f7'
            };

            function canonical(name) {
                return (name || '').toLowerCase().trim();
            }

            function getSelectedKecamatanSet() {
                const all = document.querySelector('.kecamatan-checkbox[value="all"]').checked;
                if (all) return null;

                const set = new Set();
                document.querySelectorAll('.kecamatan-checkbox:not([value="all"])').forEach(cb => {
                    if (cb.checked) set.add(canonical(cb.value));
                });
                return set;
            }

            function getColorByVisits(v) {
                if (v > 150) return '#7F2704';
                if (v > 50) return '#E6550D';
                if (v > 10) return '#FDAE6B';
                if (v > 0) return '#FEE6CE';
                return '#FFF5EB';
            }

            function getColorByKecamatan(name) {
                const kecamatanColors = {
                    'BURU': '#FF6B6B',
                    'DURAI': '#4ECDC4',
                    'KARIMUN': '#45B7D1',
                    'KUNDUR': '#FFA07A',
                    'KUNDUR BARAT': '#98D8C8',
                    'MERAL': '#F7DC6F',
                    'MORO': '#BB8FCE',
                    'TEBING': '#85C1E2'
                };
                return kecamatanColors[name] || '#95A5A6';
            }

            function getLikertStatus(v) {
                if (v === 0) return 'Tidak Pernah';
                if (v <= 10) return 'Jarang';
                if (v <= 50) return 'Normal';
                if (v <= 150) return 'Sering';
                return 'Sangat Sering';
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function truncateText(value, max = 120) {
                const text = String(value || '').trim();
                if (text.length <= max) return text;
                return text.slice(0, max).trimEnd() + '...';
            }

            function resolveImageUrl(path) {
                if (!path) return null;
                if (path.startsWith('http://') || path.startsWith('https://')) return path;
                return `/storage/${path.replace(/^\/+/, '')}`;
            }

            function buildWisataPopup(item) {
                const imageUrl = resolveImageUrl(item.gambar);
                const desc = truncateText(item.deskripsi || 'Deskripsi belum tersedia.', 135);
                const detailUrl = `${detailBaseUrl}/${item.id}`;
                const visits = Number(item.visits || 0);

                const imageBlock = imageUrl
                    ? `<img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(item.nama)}" style="width:100%;height:140px;object-fit:cover;display:block;">`
                    : `<div style="width:100%;height:140px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e2e8f0,#f8fafc);color:#64748b;font-size:13px;font-weight:600;">Foto belum tersedia</div>`;

                return `
                    <article style="background:#fff;">
                        ${imageBlock}
                        <div style="padding:14px 14px 12px;">
                            <h4 style="margin:0;font-size:18px;line-height:1.2;font-weight:800;color:#0f172a;">${escapeHtml(item.nama)}</h4>
                            <p style="margin:8px 0 0;color:#64748b;font-size:12px;font-weight:700;">${escapeHtml(item.kategori || 'Kategori tidak tersedia')}</p>
                            <p style="margin:10px 0 0;color:#334155;font-size:13px;line-height:1.5;">${escapeHtml(desc)}</p>
                            <p style="margin:10px 0 0;color:#64748b;font-size:12px;">
                                <span style="font-weight:700;">Lokasi:</span> ${escapeHtml(item.kecamatan || item.alamat || 'Karimun')}
                            </p>
                            <div style="margin:8px 0 0;color:#475569;font-size:12px;">
                                <span style="font-weight:700;">Kunjungan:</span> ${visits} (${escapeHtml(getLikertStatus(visits))})
                            </div>
                            <a href="${escapeHtml(detailUrl)}" style="display:block;margin-top:12px;background:#059669;color:#fff;text-align:center;padding:10px 12px;border-radius:10px;font-weight:700;font-size:13px;text-decoration:none;">Lihat Detail</a>
                        </div>
                    </article>
                `;
            }

            function updateLegendByMode() {
                if (currentMapMode === 'heatmap') {
                    normalLegendEl.classList.add('hidden');
                    heatLegendEl.classList.remove('hidden');
                } else {
                    heatLegendEl.classList.add('hidden');
                    normalLegendEl.classList.remove('hidden');
                }
            }

            /* ================== HITUNG KUNJUNGAN ================== */
            @foreach ($wisata as $item)
                visitsPerKecamatan['{{ $item->kecamatan }}'] =
                    (visitsPerKecamatan['{{ $item->kecamatan }}'] || 0) + {{ $item->visits ?? 0 }};
            @endforeach

            /* ================== LOAD GEOJSON ================== */
            fetch('{{ asset('geojson/kecamatan_kabkarimun.geojson') }}')
                .then(res => res.json())
                .then(data => {

                    kecamatanGeoJsonLayer = L.geoJSON(data, {
                        coordsToLatLng: coordsToLatLng,

                        style: function(feature) {
                            const name = feature.properties.NAMOBJ;
                            const visits = visitsPerKecamatan[name] || 0;
                            const selected = getSelectedKecamatanSet();
                            const active = selected === null || selected.has(canonical(name));

                            return {
                                color: '#222',
                                weight: active ? 2 : 0,
                                opacity: active ? 1 : 0,
                                fillColor: currentMapMode === 'heatmap' ? getColorByVisits(visits) : getColorByKecamatan(name),
                                fillOpacity: active ? 0.7 : 0
                            };
                        },

                        onEachFeature: function(feature, layer) {
                            const name = feature.properties.NAMOBJ;
                            const visits = visitsPerKecamatan[name] || 0;

                            layer.bindPopup(`
                        <strong>${name}</strong><br>
                        Total Kunjungan: ${visits}
                    `);

                           
                            layer.on('mouseover', () => layer.setStyle({
                                weight: 4
                            }));
                            layer.on('mouseout', () => kecamatanGeoJsonLayer.resetStyle(layer));
                        }
                    }).addTo(map);

                    // Load wisata markers AFTER GeoJSON loaded
                    wisataData.forEach(function(item) {
                        let marker = L.circleMarker(
                            [item.latitude, item.longitude],
                            {
                                radius: 10,
                                fillColor: categoryColor[item.kategori] || '#6b7280',
                                color: '#fff',
                                weight: 2,
                                fillOpacity: 0.9
                            }
                        ).addTo(wisataLayerGroup)
                        .bindPopup(buildWisataPopup(item), {
                            maxWidth: 320,
                            className: 'wisata-popup'
                        });

                        markerMap[item.id] = marker;
                    });

                    map.fitBounds(kecamatanGeoJsonLayer.getBounds());
                });

            /* ================== FILTER ================== */
            function updateFilters() {

                const category = document.querySelector('input[name="category"]:checked').value;
                const selectedKecamatan = [];
                const all = document.querySelector('.kecamatan-checkbox[value="all"]').checked;

                if (!all) {
                    document.querySelectorAll('.kecamatan-checkbox:not([value="all"])').forEach(cb => {
                        if (cb.checked) selectedKecamatan.push(cb.value);
                    });
                }

                let count = 0;
                let maxVisibleVisits = 0;

                document.querySelectorAll('.wisata-item').forEach(item => {
                    const okCategory = category === 'all' || item.dataset.wisataCategory === category;
                    const okKec = selectedKecamatan.length === 0 ||
                        selectedKecamatan.includes(item.dataset.wisataKecamatan);

                    const visible = okCategory && okKec;
                    if (!visible) return;

                    const wisataObj = wisataData.find(w => String(w.id) === String(item.dataset.wisataId));
                    const v = Number(wisataObj?.visits || 0);
                    if (v > maxVisibleVisits) maxVisibleVisits = v;
                });

                document.querySelectorAll('.wisata-item').forEach(item => {
                    const okCategory = category === 'all' || item.dataset.wisataCategory === category;
                    const okKec = selectedKecamatan.length === 0 ||
                        selectedKecamatan.includes(item.dataset.wisataKecamatan);

                    const visible = okCategory && okKec;
                    item.style.display = visible ? 'block' : 'none';

                    if (markerMap[item.dataset.wisataId]) {
                        const wisataObj = wisataData.find(w => String(w.id) === String(item.dataset.wisataId));
                        const visits = Number(wisataObj?.visits || 0);
                        const baseRadius = currentMapMode === 'heatmap'
                            ? Math.max(6, Math.min(16, 6 + (maxVisibleVisits > 0 ? (visits / maxVisibleVisits) * 10 : 0)))
                            : 10;

                        markerMap[item.dataset.wisataId].setStyle({
                            radius: baseRadius,
                            fillColor: currentMapMode === 'heatmap'
                                ? getColorByVisits(visits)
                                : (categoryColor[wisataObj?.kategori] || '#6b7280'),
                            color: '#fff',
                            weight: 2,
                            opacity: visible ? 1 : 0.2,
                            fillOpacity: visible ? 0.9 : 0.2
                        });
                    }

                    if (visible) count++;
                });

                document.getElementById('wisataCount').textContent = count;

                if (kecamatanGeoJsonLayer) {
                    kecamatanGeoJsonLayer.setStyle(feature =>
                        kecamatanGeoJsonLayer.options.style(feature)
                    );
                }
            }

            document.querySelectorAll('input').forEach(el =>
                el.addEventListener('change', updateFilters)
            );

            document.querySelectorAll('input[name="mapMode"]').forEach(el => {
                el.addEventListener('change', () => {
                    currentMapMode = document.querySelector('input[name="mapMode"]:checked').value;
                    updateLegendByMode();

                    if (kecamatanGeoJsonLayer) {
                        kecamatanGeoJsonLayer.setStyle(feature =>
                            kecamatanGeoJsonLayer.options.style(feature)
                        );
                    }

                    updateFilters();
                });
            });

            updateLegendByMode();

        });
    </script>

@endsection
