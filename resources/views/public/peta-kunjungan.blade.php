@extends('layouts.public')

@section('title', 'Peta Kunjungan Wisata - Karimun')

@section('content')
    <div class="h-[calc(100vh-64px)] flex flex-col bg-gray-100 overflow-hidden">
        <div class="bg-white shadow-md z-10 p-5 shrink-0 relative">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Peta Agregat Kunjungan</h2>
                    <p class="text-sm text-gray-500 mt-1">Total kunjungan wisata seluruh Kabupaten Karimun berdasarkan tahun.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-5 sm:gap-6 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tahun Kunjungan</label>
                        <select id="tahunSelect" class="w-36 px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm bg-white font-medium text-gray-700 shadow-sm cursor-pointer transition-all">
                            @foreach (($kunjunganTahunanKarimun ?? []) as $tahun => $total)
                                <option value="{{ $tahun }}" @selected($loop->last)>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hidden sm:block h-10 w-px bg-gray-200 mx-1"></div>

                    <div class="flex flex-col min-w-[140px]">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Total Kunjungan</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="totalKunjungan" class="text-3xl font-black text-blue-700 leading-none tracking-tight">0</span>
                            <span class="text-sm font-semibold text-gray-500">kunjungan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 relative z-0">
            <div id="map" class="w-full h-full"></div>
        </div>
    </div>

    <style>
        .info.legend {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.05);
            color: #374151;
            font-family: inherit;
            min-width: 260px;
        }

        .legend-title {
            font-size: 12px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 6px;
            text-transform: uppercase;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            margin-right: 8px;
            flex-shrink: 0;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.12);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kunjunganTahunanKarimun = {!! json_encode($kunjunganTahunanKarimun ?? []) !!};
            const yearSelect = document.getElementById('tahunSelect');
            const totalKunjunganEl = document.getElementById('totalKunjungan');

            const map = L.map('map', { zoomControl: false });
            L.control.zoom({ position: 'topleft' }).addTo(map);

            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const satelitLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri &mdash; Esri, iCube, Earthstar Geographics'
            });

            L.control.layers({
                "OpenStreetMap": osmLayer,
                "Satelit": satelitLayer
            }, {}, { collapsed: false, position: 'topright' }).addTo(map);

            let geojsonData = null;
            let kunjunganLayer = null;
            const fixedYearColors = {
                '2021': '#FFF7BC',
                '2022': '#FDD049',
                '2023': '#FD8D3C',
                '2024': '#D1D5DB',
                '2025': '#BD0026'
            };
            const extraYearColorPalette = [
                '#8B5CF6',
                '#0EA5E9',
                '#10B981',
                '#EC4899',
                '#6366F1',
                '#84CC16',
                '#F97316',
                '#14B8A6'
            ];
            const yearColors = buildYearColors();

            function coordsToLatLng(coords) {
                return L.latLng(coords[1], coords[0]);
            }

            function formatNumber(value) {
                return new Intl.NumberFormat('id-ID').format(Number(value || 0));
            }

            function buildYearColors() {
                let extraColorIndex = 0;

                return Object.keys(kunjunganTahunanKarimun).reduce((result, year) => {
                    if (fixedYearColors[year]) {
                        result[year] = fixedYearColors[year];
                    } else {
                        result[year] = extraYearColorPalette[extraColorIndex % extraYearColorPalette.length];
                        extraColorIndex++;
                    }

                    return result;
                }, {});
            }

            function getColorByYear(year) {
                return yearColors[year] || '#6B7280';
            }

            function renderKunjunganLayer() {
                if (!geojsonData) return;

                if (kunjunganLayer) {
                    map.removeLayer(kunjunganLayer);
                }

                const selectedYear = yearSelect.value;
                const totalVisits = Number(kunjunganTahunanKarimun[selectedYear] || 0);
                totalKunjunganEl.textContent = formatNumber(totalVisits);

                kunjunganLayer = L.geoJSON(geojsonData, {
                    coordsToLatLng: coordsToLatLng,
                    style: function () {
                        return {
                            color: '#1f2937',
                            weight: 1.4,
                            opacity: 0.85,
                            fillColor: getColorByYear(selectedYear),
                            fillOpacity: 0.78
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(`<div style="text-align:center;color:#991b1b;"><strong>Kunjungan Wisata Karimun ${selectedYear}</strong><br><span style="font-size:13px;font-weight:bold;color:#4b5563;">${formatNumber(totalVisits)} kunjungan</span></div>`);

                        layer.on('mouseover', () => {
                            layer.setStyle({ weight: 3, fillOpacity: 0.92 });
                        });
                        layer.on('mouseout', () => {
                            kunjunganLayer.resetStyle(layer);
                        });
                    }
                }).addTo(map);
            }

            const legend = L.control({ position: 'bottomleft' });
            legend.onAdd = function () {
                const div = L.DomUtil.create('div', 'info legend');
                let html = `<div class="legend-title">Kunjungan Wisata</div>`;

                Object.entries(kunjunganTahunanKarimun).forEach(([year, total]) => {
                    html += `<div class="legend-item"><div class="legend-color" style="background:${getColorByYear(year)};"></div>${year}: ${formatNumber(total)} kunjungan</div>`;
                });

                div.innerHTML = html;
                L.DomEvent.disableClickPropagation(div);

                return div;
            };
            legend.addTo(map);

            yearSelect.addEventListener('change', renderKunjunganLayer);

            fetch('{{ asset('geojson/karimun.geojson') }}?v={{ time() }}')
                .then(res => res.json())
                .then(data => {
                    geojsonData = data;
                    renderKunjunganLayer();

                    if (kunjunganLayer) {
                        map.fitBounds(kunjunganLayer.getBounds(), { padding: [50, 50] });
                    }
                });
        });
    </script>
@endsection
