<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\WisataVisit;
use App\Models\WisataReview;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya ambil latitude, longitude, nama, kategori (minimal data)
        $wisataData = Wisata::select('id', 'nama', 'latitude', 'longitude', 'kategori', 'harga')
            ->limit(200) // Limit untuk performa
            ->get();

        // Hitung kategori dengan CACHE
        $kategoriCount = Wisata::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        // Total ulasan dari semua wisata
        $totalUlasan = (int) WisataReview::count();

        // Total kunjungan dari kolom agregat wisata
        $totalKunjungan = (int) Wisata::sum('visits');

        // Data untuk chart kunjungan wisata per tahun
        $wisataPerTahun = WisataVisit::selectRaw('YEAR(visited_at) as tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        // Detail wisata per tahun untuk tooltip
        $wisataDetailPerTahun = WisataVisit::selectRaw('YEAR(visited_at) as tahun, wisata_id, COUNT(*) as jumlah')
            ->with('wisata:id,nama')
            ->groupBy('tahun', 'wisata_id')
            ->orderBy('tahun', 'asc')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->groupBy('tahun')
            ->map(function ($items) {
                return $items->take(5)->map(function ($item) {
                    return [
                        'nama' => $item->wisata->nama ?? 'N/A',
                        'jumlah' => $item->jumlah
                    ];
                });
            });

        // Daftar tahun tersedia untuk filter (dari data kunjungan)
        $tahunTersedia = WisataVisit::selectRaw('YEAR(visited_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Jika tidak ada data kunjungan, gunakan tahun sekarang
        if ($tahunTersedia->isEmpty()) {
            $tahunTersedia = collect([date('Y')]);
        }

        // Data kunjungan per wisata (top 10 wisata dengan kunjungan terbanyak)
        $kunjunganPerWisata = WisataVisit::selectRaw('wisata_id, COUNT(*) as total_kunjungan')
            ->groupBy('wisata_id')
            ->orderBy('total_kunjungan', 'desc')
            ->limit(10)
            ->with('wisata:id,nama,kategori,alamat')
            ->get();

        return view('admin.dashboard', compact('wisataData', 'kategoriCount', 'totalUlasan', 'totalKunjungan', 'wisataPerTahun', 'tahunTersedia', 'kunjunganPerWisata', 'wisataDetailPerTahun'));
    }

    // Method untuk mendapatkan data kunjungan wisata per hari berdasarkan bulan dan tahun (untuk AJAX)
    public function getWisataPerHari(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));

        $wisataPerHariRaw = WisataVisit::selectRaw('DAY(visited_at) as hari, COUNT(*) as total')
            ->whereYear('visited_at', $tahun)
            ->whereMonth('visited_at', $bulan)
            ->groupBy('hari')
            ->pluck('total', 'hari')
            ->toArray();

        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan)->daysInMonth;

        $wisataPerHari = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $wisataPerHari[] = [
                'hari' => $i,
                'total' => $wisataPerHariRaw[$i] ?? 0
            ];
        }

        // Detail wisata per hari
        $wisataDetailPerHari = WisataVisit::selectRaw('DAY(visited_at) as hari, wisata_id, COUNT(*) as jumlah')
            ->whereYear('visited_at', $tahun)
            ->whereMonth('visited_at', $bulan)
            ->with('wisata:id,nama')
            ->groupBy('hari', 'wisata_id')
            ->orderBy('hari', 'asc')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->groupBy('hari')
            ->map(function ($items) {
                return $items->take(5)->map(function ($item) {
                    return [
                        'nama' => $item->wisata->nama ?? 'N/A',
                        'jumlah' => $item->jumlah
                    ];
                });
            });

        return response()->json([
            'data' => $wisataPerHari,
            'details' => $wisataDetailPerHari
        ]);
    }
}
