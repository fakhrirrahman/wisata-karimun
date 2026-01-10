<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Wisata Karimun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white fixed h-screen overflow-y-auto">
            <div class="p-6 border-b border-gray-800">
                <h1 class="text-2xl font-bold">🗺️ Wisata Karimun</h1>
                <p class="text-sm text-gray-400">Admin Panel</p>
            </div>

            <nav class="mt-6 px-3">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('datawisata.index') }}" class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('datawisata.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    <span>Data Wisata</span>
                </a>

                <a href="{{ route('kunjungan.index') }}" class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('kunjungan.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-eye mr-3"></i>
                    <span>Data Kunjungan</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profil Saya</span>
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 overflow-auto">
            <!-- Top Navbar -->
            <div class="bg-white shadow-sm p-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">@yield('page-title')</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->nama }}</span>
                    @if(Auth::user()->gambar)
                        <img src="{{ asset('storage/' . Auth::user()->gambar) }}" alt="{{ Auth::user()->nama }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
