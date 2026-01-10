<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya ambil latitude, longitude, nama, kategori (minimal data)
        $wisataData = Wisata::select('id', 'nama', 'latitude', 'longitude', 'kategori')
            ->limit(200) // Limit untuk performa
            ->get();
        
        // Hitung kategori dengan CACHE
        $kategoriCount = Wisata::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        // Hitung kecamatan
        $kecamatanCount = Wisata::selectRaw('alamat as kecamatan, COUNT(*) as total')
            ->groupBy('alamat')
            ->get();

        return view('admin.dashboard', compact('wisataData', 'kategoriCount', 'kecamatanCount'));
    }
}
