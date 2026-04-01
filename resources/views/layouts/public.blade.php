<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Wisata Karimun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('beranda') }}" class="flex items-center space-x-2">
                    <i class="fas fa-map text-blue-600 text-2xl"></i>
                    <span class="font-bold text-xl">Wisata Karimun</span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('beranda') }}" class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('beranda') ? 'text-blue-600 font-bold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('peta') }}" class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('peta') ? 'text-blue-600 font-bold' : '' }}">
                        Peta Wisata
                    </a>
                    <a href="{{ route('peta-kecamatan') }}" class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('peta-kecamatan') ? 'text-blue-600 font-bold' : '' }}">
                        Peta Kecamatan
                    </a>
                </div>

                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Login Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @unless(request()->routeIs('peta'))
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="font-bold mb-4">Tentang</h3>
                    <p class="text-gray-400">Sistem Informasi Geografis Pemetaan Lokasi Wisata Karimun</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('beranda') }}" class="hover:text-white">Beranda</a></li>
                        <li><a href="{{ route('peta') }}" class="hover:text-white">Peta Wisata</a></li>
                        <li><a href="{{ route('peta-kecamatan') }}" class="hover:text-white">Peta Kecamatan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Kontak</h3>
                    <p class="text-gray-400">Karimun, Indonesia</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Wisata Karimun. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endunless
</body>
</html>
