@extends('layouts.public')

@section('title', 'Peta Kecamatan - Karimun')

@section('content')
<div style="height: 100vh; display: flex; flex-direction: column;" class="md:flex-row">
    <!-- Sidebar Filter -->
    <aside class="w-full md:w-80 bg-white shadow-lg overflow-y-auto order-2 md:order-1">
        <div class="p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Filter Peta</h2>
            <p class="text-gray-600 text-sm">Wisata Karimun</p>
        </div>

        <!-- Fasilitas Filter -->
        <div class="p-6 border-b">
            <label class="block text-sm font-semibold text-gray-700 mb-4 uppercase">Fasilitas</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="all" checked>
                    <span class="ml-2 text-gray-700">Semua Fasilitas</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Toilet">
                    <span class="ml-2 text-gray-700">✓ Toilet</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Mushola">
                    <span class="ml-2 text-gray-700">🕌 Mushola</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Tempat Makan">
                    <span class="ml-2 text-gray-700">🍽️ Tempat Makan</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Parkir">
                    <span class="ml-2 text-gray-700">🅿️ Parkir</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Kamar Bilas">
                    <span class="ml-2 text-gray-700">🚿 Kamar Bilas</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 fasilitas-checkbox" value="Toko Souvenir">
                    <span class="ml-2 text-gray-700">🛍️ Toko Souvenir</span>
                </label>
            </div>
        </div>

        <!-- Kategori Filter -->
        <div class="p-6 border-b">
            <label class="block text-sm font-semibold text-gray-700 mb-4 uppercase">Kategori</label>
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
            <label class="block text-sm font-semibold text-gray-700 mb-4 uppercase">Kecamatan</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="all" checked>
                    <span class="ml-2 text-gray-700">Tampilkan Semua</span>
                </label>
                <label class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2" style="background-color: #FF6B6B;"></div>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="Muara Bulian">
                    <span class="ml-2 text-gray-700">Muara Bulian</span>
                </label>
                <label class="flex items-center">
                    <div class="w-4 h-4 bg-teal-500 rounded mr-2" style="background-color: #4ECDC4;"></div>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="Sungai Gelam">
                    <span class="ml-2 text-gray-700">Sungai Gelam</span>
                </label>
                <label class="flex items-center">
                    <div class="w-4 h-4 bg-emerald-300 rounded mr-2" style="background-color: #95E1D3;"></div>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="Terusan">
                    <span class="ml-2 text-gray-700">Terusan</span>
                </label>
                <label class="flex items-center">
                    <div class="w-4 h-4 rounded mr-2" style="background-color: #FFE66D;"></div>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="Rantau Rasau">
                    <span class="ml-2 text-gray-700">Rantau Rasau</span>
                </label>
                <label class="flex items-center">
                    <div class="w-4 h-4 bg-emerald-200 rounded mr-2" style="background-color: #A8E6CF;"></div>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 kecamatan-checkbox" value="Tabir Ilir">
                    <span class="ml-2 text-gray-700">Tabir Ilir</span>
                </label>
            </div>
        </div>

        <!-- Legenda Kategori -->
        <div class="p-6 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase">Legenda Marker</h3>
            <div class="space-y-2 text-xs">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                    <span>Wisata Alam</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                    <span>Wisata Budaya</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                    <span>Wisata Buatan</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                    <span>Wisata Kuliner</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 rounded-full mr-2"></div>
                    <span>Wisata Kerajinan</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Map Container -->
    <div id="map" style="flex: 1; min-height: 400px; background-color: #e5e7eb;"></div>
</div>

<script>
    // Tunggu DOM selesai loading
    document.addEventListener('DOMContentLoaded', function() {
        // Warna untuk setiap kategori
        const categoryColors = {
            'Wisata Alam': '#22c55e',
            'Wisata Budaya': '#ef4444',
            'Wisata Buatan': '#3b82f6',
            'Wisata Kuliner': '#eab308',
            'Wisata Kerajinan': '#f97316'
        };

        // Inisialisasi map
        const map = L.map('map').setView([1.0535, 103.3870], 14);
        
        // Tambah tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);

        let wisataData = [];
        let wisataMarkers = [];
        let visibleMarkers = [];

        // Load GeoJSON kecamatan
        fetch('/geojson/kecamatan.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            fillColor: feature.properties.COLOR,
                            color: '#333333',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.7
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const kecamatan = feature.properties.KECAMATAN;
                        layer.on('mouseover', function() {
                            this.setStyle({weight: 3, fillOpacity: 0.9});
                        });
                        layer.on('mouseout', function() {
                            this.setStyle({weight: 2, fillOpacity: 0.7});
                        });
                        layer.bindPopup('<strong>' + kecamatan + '</strong>');
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Error loading GeoJSON:', error));

        // Load data wisata dari API
        fetch('{{ route("api.wisata.all") }}')
            .then(response => response.json())
            .then(data => {
                wisataData = data;
                loadWisata();
            })
            .catch(error => {
                console.log('Menggunakan dummy data');
                wisataData = [
                    {id: 1, nama: 'Pantai Carocok', kategori: 'Wisata Alam', latitude: 1.053, longitude: 103.370, fasilitas: ['Toilet', 'Parkir']},
                    {id: 2, nama: 'Taman Budaya', kategori: 'Wisata Budaya', latitude: 1.054, longitude: 103.371, fasilitas: ['Toilet']},
                    {id: 3, nama: 'Taman Hiburan', kategori: 'Wisata Buatan', latitude: 1.052, longitude: 103.369, fasilitas: ['Toilet', 'Tempat Makan']},
                    {id: 4, nama: 'Warung Kopi', kategori: 'Wisata Kuliner', latitude: 1.051, longitude: 103.368, fasilitas: ['Tempat Makan']},
                    {id: 5, nama: 'Kerajinan Tangan', kategori: 'Wisata Kerajinan', latitude: 1.055, longitude: 103.372, fasilitas: ['Toko Souvenir']}
                ];
                loadWisata();
            });

        function loadWisata() {
            wisataMarkers = [];
            wisataData.forEach(wisata => {
                const lat = parseFloat(wisata.latitude);
                const lng = parseFloat(wisata.longitude);
                const color = categoryColors[wisata.kategori] || '#999';
                
                const marker = L.circleMarker([lat, lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                });

                const popup = `<div><strong>${wisata.nama}</strong><br>${wisata.kategori}${wisata.fasilitas && wisata.fasilitas.length > 0 ? '<br>' + wisata.fasilitas.join(', ') : ''}</div>`;
                marker.bindPopup(popup);
                marker.wisataData = wisata;
                wisataMarkers.push(marker);
            });

            filterWisata();
        }

        function filterWisata() {
            const selectedCategory = document.querySelector('input[name="category"]:checked').value;
            const selectedFasilitas = Array.from(document.querySelectorAll('.fasilitas-checkbox:checked')).map(cb => cb.value);

            visibleMarkers.forEach(marker => map.removeLayer(marker));
            visibleMarkers = [];

            wisataMarkers.forEach(marker => {
                const data = marker.wisataData;
                
                if (selectedCategory !== 'all' && data.kategori !== selectedCategory) return;

                if (selectedFasilitas.length > 0 && !selectedFasilitas.includes('all')) {
                    const hasFasilitas = data.fasilitas && data.fasilitas.some(f => selectedFasilitas.includes(f));
                    if (!hasFasilitas) return;
                }

                marker.addTo(map);
                visibleMarkers.push(marker);
            });
        }

        // Event listeners
        document.querySelectorAll('input[name="category"], .fasilitas-checkbox').forEach(input => {
            input.addEventListener('change', filterWisata);
        });
    });
</script>
@endsection
