@extends('layouts.public')

@section('title', 'Peta Wisata - Karimun')

@section('content')
    <div class="h-[calc(100vh-64px)] flex flex-col bg-gray-100 overflow-hidden">
        <!-- Top Bar Filters -->
        <div class="bg-white shadow-md z-10 p-4 shrink-0 flex flex-wrap items-end gap-4 relative">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Cari Wisata</label>
                <input type="text" id="searchInput"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none text-sm"
                    placeholder="Nama wisata...">
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Kecamatan</label>
                <select id="kecamatanSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none text-sm bg-white">
                    <option value="all">Semua Kecamatan</option>
                    @php
                        $kecamatanList = [
                            'BURU', 'DURAI', 'KARIMUN', 'KUNDUR', 'KUNDUR BARAT', 'MERAL', 'MORO', 'TEBING'
                        ];
                    @endphp
                    @foreach ($kecamatanList as $kec)
                        <option value="{{ $kec }}">{{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Kategori</label>
                <select id="kategoriSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none text-sm bg-white">
                    <option value="all">Semua Kategori</option>
                    <option value="Wisata Alam">Wisata Alam</option>
                    <option value="Wisata Bahari">Wisata Bahari</option>
                    <option value="Wisata Buatan">Wisata Buatan</option>
                    <option value="Cagar Budaya">Cagar Budaya</option>
                    <option value="Wisata Belanja">Wisata Belanja</option>
                    <option value="Wisata Heritage">Wisata Heritage</option>
                    <option value="Wisata Sejarah">Wisata Sejarah</option>
                    <option value="Wisata Budaya">Wisata Budaya</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Bulan</label>
                <select id="bulanSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none text-sm bg-white">
                    <option value="all">Semua Bulan</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Tahun</label>
                <select id="tahunSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 outline-none text-sm bg-white">
                    <option value="all">Semua Tahun</option>
                    @php
                        $startYear = 2020;
                        $currentYear = date('Y');
                    @endphp
                    @for ($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <button id="resetFilterBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition text-sm font-semibold h-[38px]">
                    Reset Filter
                </button>
            </div>
        </div>

        <div class="bg-white px-4 py-1.5 border-b shadow-sm flex justify-between items-center text-xs text-gray-600 shrink-0">
            <span>Ditemukan: <strong id="wisataCount" class="text-blue-600">{{ count($wisata) }}</strong> wisata</span>
            <span class="italic font-medium">Buka panel <strong>Layer</strong> di sudut kanan atas peta untuk mengaktifkan Heatmap.</span>
        </div>

        <!-- Map -->
        <div class="flex-1 relative z-0">
            <div id="map" class="w-full h-full"></div>
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

        /* Custom Legend Control Styles */
        .info.legend {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.05);
            color: #374151;
            font-family: inherit;
            min-width: 300px;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 16px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
        }
        
        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            margin-right: 8px;
            flex-shrink: 0;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);
        }

        .legend-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            border: 2px solid white;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .legend-section-title {
            font-size: 12px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 10px;
            margin-top: 14px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .legend-section-title:first-child {
            margin-top: 0;
        }
        
        /* Layer Control specific styling adjustment */
        .leaflet-control-layers {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
            padding: 4px;
        }
        .leaflet-control-layers-list {
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ================== MAP INIT ================== */
            // Inisialisasi peta dan pindahkan zoom control agar tidak menimpa layer
            const map = L.map('map', {
                zoomControl: false 
            });
            L.control.zoom({ position: 'topleft' }).addTo(map);
            map.createPane('heatmapPane');
            map.getPane('heatmapPane').style.zIndex = 450;

            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            });

            const satelitLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri &mdash; Esri, iCube, Earthstar Geographics'
            });

            // Set default Map Layer
            osmLayer.addTo(map);

            function coordsToLatLng(coords) {
                return L.latLng(coords[1], coords[0]); 
            }

            /* ================== STATE & LAYERS ================== */
            const wisataLayerGroup = L.layerGroup().addTo(map);
            const batasKecamatanGroup = L.layerGroup().addTo(map); // Menyala by default
            const heatmapGroup = L.layerGroup().addTo(map); // Heatmap kunjungan tampil default
            
            let markerMap = {};
            let jumlahKunjunganPerKecamatan = {!! json_encode($jumlahKunjunganPerKecamatan ?? []) !!};
            let heatmapKunjunganPoints = {!! json_encode($heatmapKunjunganPoints ?? []) !!};
            const wisataData = {!! json_encode($wisata) !!};
            let geojsonData = null;
            let currentGeoJsonLayerBatas = null;
            let heatmapLayer = null;
            
            const detailBaseUrl = @json(url('/detail'));

            const categoryColor = {
                'Wisata Alam': '#22c55e',
                'Wisata Bahari': '#0ea5e9',
                'Wisata Buatan': '#3b82f6',
                'Cagar Budaya': '#ef4444',
                'Wisata Belanja': '#ec4899',
                'Wisata Heritage': '#f59e0b',
                'Wisata Sejarah': '#8b5cf6',
                'Wisata Budaya': '#a855f7'
            };

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

            function canonical(name) {
                return (name || '').toLowerCase().trim();
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
                    : `<div style="width:100%;height:140px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f1f5f9,#ffffff);color:#64748b;font-size:13px;font-weight:600;">Foto belum tersedia</div>`;

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
                            <div style="margin:8px 0 0;color:#ef4444;font-size:12px;font-weight:700;">
                                <i class="fas fa-fire mr-1"></i>Kunjungan: ${visits} kali
                            </div>
                            <a href="${escapeHtml(detailUrl)}" style="display:block;margin-top:12px;background:#2563eb;color:#fff;text-align:center;padding:10px 12px;border-radius:10px;font-weight:700;font-size:13px;text-decoration:none;">Lihat Detail</a>
                        </div>
                    </article>
                `;
            }

            /* ================== LEAFLET LAYER CONTROLS ================== */
            const baseMapsConfig = {
                "OpenStreetMap": osmLayer,
                "Satelit": satelitLayer
            };

            const overlayMapsConfig = {
                "Semua Batas Kecamatan": batasKecamatanGroup,
                "Peta Kunjungan (Heatmap)": heatmapGroup,
                "Sebaran Lokasi Wisata": wisataLayerGroup
            };

            L.control.layers(baseMapsConfig, overlayMapsConfig, { collapsed: false, position: 'topright' }).addTo(map);

            /* ================== CUSTOM LEGEND CONTROL ================== */
            const legend = L.control({ position: 'bottomleft' });

            legend.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'info legend');
                
                let html = `<div class="legend-section-title">Batas Wilayah Kecamatan</div>`;
                html += `<div class="legend-grid">`;
                for (let kec in kecamatanColors) {
                    html += `<div class="legend-item"><div class="legend-color" style="background-color: ${kecamatanColors[kec]}; opacity: 0.7;"></div>${kec}</div>`;
                }
                html += `</div>`;

                html += `<div class="legend-section-title">Kategori Wisata</div>`;
                html += `<div class="legend-grid">`;
                for (let kat in categoryColor) {
                    html += `<div class="legend-item"><div class="legend-circle" style="background-color: ${categoryColor[kat]};"></div>${kat}</div>`;
                }
                html += `</div>`;

                html += `<div class="legend-section-title">Peta Kunjungan (Heatmap)</div>`;
                html += `<div class="legend-grid" style="grid-template-columns: 1fr;">`;
                const heatmaps = [
                    { label: '0 Kunjungan', color: '#fee5d9' },
                    { label: '1 - 5.000 Kunjungan', color: '#fcae91' },
                    { label: '5.001 - 10.000 Kunjungan', color: '#fb6a4a' },
                    { label: '10.001 - 25.000 Kunjungan', color: '#de2d26' },
                    { label: '> 25.000 Kunjungan', color: '#a50f15' }
                ];
                heatmaps.forEach(h => {
                    html += `<div class="legend-item"><div class="legend-color" style="background-color: ${h.color}; opacity: 0.9;"></div>${h.label}</div>`;
                });
                html += `</div>`;

                div.innerHTML = html;
                
                // Mencegah interaksi klik di legend tembus ke map
                L.DomEvent.disableClickPropagation(div);
                
                return div;
            };

            legend.addTo(map);

            /* ================== LOGIKA FILTER & RENDER ================== */
            const searchInput = document.getElementById('searchInput');
            const katSelect = document.getElementById('kategoriSelect');
            const kecSelect = document.getElementById('kecamatanSelect');
            const bulanSelect = document.getElementById('bulanSelect');
            const tahunSelect = document.getElementById('tahunSelect');
            const wisataCountEl = document.getElementById('wisataCount');
            const resetBtn = document.getElementById('resetFilterBtn');

            async function fetchHeatmapData() {
                const bulan = bulanSelect.value;
                const tahun = tahunSelect.value;
                
                try {
                    const [kecamatanResponse, pointsResponse] = await Promise.all([
                        fetch(`/api/kunjungan/heatmap?bulan=${bulan}&tahun=${tahun}`),
                        fetch(`/api/kunjungan/heatmap-points?bulan=${bulan}&tahun=${tahun}`)
                    ]);
                    
                    jumlahKunjunganPerKecamatan = await kecamatanResponse.json();
                    heatmapKunjunganPoints = await pointsResponse.json();
                    renderGeoJsonLayers();
                    renderHeatmapLayer();
                } catch (error) {
                    console.error('Error fetching heatmap data:', error);
                }
            }

            bulanSelect.addEventListener('change', fetchHeatmapData);
            tahunSelect.addEventListener('change', fetchHeatmapData);

            function renderGeoJsonLayers() {
                if (!geojsonData) return;
                
                // Bersihkan layer sebelumnya
                batasKecamatanGroup.clearLayers();

                const selectedFilterKec = canonical(kecSelect.value);

                // --- 1. Batas Kecamatan Layer ---
                currentGeoJsonLayerBatas = L.geoJSON(geojsonData, {
                    coordsToLatLng: coordsToLatLng,
                    style: function (feature) {
                        const nameUpper = (feature.properties.NAMOBJ || '').toUpperCase();
                        const nameCanon = canonical(feature.properties.NAMOBJ);
                        
                        // Menyorot kecamatan yang sedang dipilih
                        const isSelected = selectedFilterKec === 'all' || selectedFilterKec === nameCanon;
                        
                        return {
                            color: isSelected ? '#1f2937' : '#9ca3af',
                            weight: isSelected ? 2 : 1,
                            opacity: isSelected ? 0.9 : 0.4,
                            fillColor: kecamatanColors[nameUpper] || '#95A5A6',
                            fillOpacity: isSelected ? 0.22 : 0.04
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        const nameUpper = (feature.properties.NAMOBJ || '').toUpperCase();
                        const visits = jumlahKunjunganPerKecamatan[nameUpper] || 0;
                        layer.bindPopup(`<div style="text-align:center;"><strong>Kec. ${feature.properties.NAMOBJ}</strong><br><span style="font-size:12px;color:#6b7280;">Jumlah Kunjungan: ${new Intl.NumberFormat('id-ID').format(visits)}</span></div>`);
                        
                        // Efek Hover
                        layer.on('mouseover', () => {
                            layer.setStyle({ weight: 3, fillOpacity: 0.7 });
                        });
                        layer.on('mouseout', () => {
                            currentGeoJsonLayerBatas.resetStyle(layer);
                        });
                    }
                }).addTo(batasKecamatanGroup);
            }

            function renderHeatmapLayer() {
                heatmapGroup.clearLayers();

                const keyword = canonical(searchInput.value);
                const valKat = katSelect.value;
                const valKec = canonical(kecSelect.value);
                const maxTotal = Math.max(...heatmapKunjunganPoints.map(item => Number(item.total || 0)), 1);
                const heatData = heatmapKunjunganPoints
                    .filter(item => {
                        const lat = Number(item.latitude);
                        const lng = Number(item.longitude);
                        const total = Number(item.total || 0);
                        const matchKec = valKec === 'all' || canonical(item.kecamatan) === valKec;
                        const matchKat = valKat === 'all' || item.kategori === valKat;
                        const matchKey = keyword === '' || canonical(item.nama).includes(keyword);

                        return Number.isFinite(lat) && Number.isFinite(lng) && total > 0 && matchKec && matchKat && matchKey;
                    })
                    .map(item => [
                        Number(item.latitude),
                        Number(item.longitude),
                        Math.max(Number(item.total || 0) / maxTotal, 0.15)
                    ]);

                heatmapLayer = L.heatLayer(heatData, {
                    pane: 'heatmapPane',
                    radius: 46,
                    blur: 32,
                    maxZoom: 14,
                    minOpacity: 0.45,
                    gradient: {
                        0.18: '#fef08a',
                        0.4: '#fb923c',
                        0.68: '#ef4444',
                        1: '#7f1d1d'
                    }
                }).addTo(heatmapGroup);
            }

            function applyFilters() {
                const keyword = canonical(searchInput.value);
                const valKat = katSelect.value;
                const valKec = canonical(kecSelect.value);

                let count = 0;

                wisataData.forEach(item => {
                    const matchKec = valKec === 'all' || canonical(item.kecamatan) === valKec;
                    const matchKat = valKat === 'all' || item.kategori === valKat;
                    const matchKey = keyword === '' || canonical(item.nama).includes(keyword);

                    const visible = matchKec && matchKat && matchKey;
                    
                    if (markerMap[item.id]) {
                        if (visible) {
                            if (!wisataLayerGroup.hasLayer(markerMap[item.id])) {
                                wisataLayerGroup.addLayer(markerMap[item.id]);
                            }
                        } else {
                            if (wisataLayerGroup.hasLayer(markerMap[item.id])) {
                                wisataLayerGroup.removeLayer(markerMap[item.id]);
                            }
                        }
                    }

                    if (visible) count++;
                });

                wisataCountEl.textContent = count;
                
                // Menata ulang style/highlight GeoJSON berdasarkan kecamatan yang di-filter
                renderGeoJsonLayers();
                renderHeatmapLayer();
            }

            // Inisialisasi Titik Wisata Marker
            wisataData.forEach(function (item) {
                let marker = L.circleMarker(
                    [item.latitude, item.longitude],
                    {
                        radius: 8,
                        fillColor: categoryColor[item.kategori] || '#6b7280',
                        color: '#ffffff',
                        weight: 2,
                        fillOpacity: 0.95,
                        opacity: 1
                    }
                );
                
                marker.bindPopup(buildWisataPopup(item), { maxWidth: 320, className: 'wisata-popup' });
                markerMap[item.id] = marker;
                marker.addTo(wisataLayerGroup);
            });

            // Pengambilan dan Pemasangan Data GeoJSON
            fetch('{{ asset('geojson/jml_wisata.geojson') }}?v={{ time() }}')
                .then(res => res.json())
                .then(data => {
                    geojsonData = data;
                    renderGeoJsonLayers();
                    
                    // Memfokuskan tampilan peta ke GeoJSON
                    if (currentGeoJsonLayerBatas) {
                        map.fitBounds(currentGeoJsonLayerBatas.getBounds(), { padding: [50, 50] });
                    }
                });

            // Event Listener untuk elemen Filter
            searchInput.addEventListener('input', applyFilters);
            katSelect.addEventListener('change', applyFilters);
            kecSelect.addEventListener('change', applyFilters);

            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                katSelect.value = 'all';
                kecSelect.value = 'all';
                bulanSelect.value = 'all';
                tahunSelect.value = 'all';
                applyFilters();
                fetchHeatmapData();
                
                // Jika ingin mereset tampilan zoom juga
                if (currentGeoJsonLayerBatas) {
                    map.fitBounds(currentGeoJsonLayerBatas.getBounds(), { padding: [50, 50] });
                }
            });

        });
    </script>
@endsection
