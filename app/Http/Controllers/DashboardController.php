<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\WisataVisit;

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

        return view('admin.dashboard', compact('wisataData', 'kategoriCount', 'totalKunjungan', 'wisataPerTahun', 'tahunTersedia', 'kunjunganPerWisata', 'wisataDetailPerTahun'));
    }

    // Method untuk mendapatkan data kunjungan wisata per bulan berdasarkan tahun (untuk AJAX)
    public function getWisataPerBulan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $wisataPerBulan = WisataVisit::selectRaw('MONTH(visited_at) as bulan, COUNT(*) as total')
            ->whereYear('visited_at', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->map(function ($item) {
                $namaBulan = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                return [
                    'bulan' => $namaBulan[$item->bulan],
                    'bulan_number' => $item->bulan,
                    'total' => $item->total
                ];
            });

        // Detail wisata per bulan
        $wisataDetailPerBulan = WisataVisit::selectRaw('MONTH(visited_at) as bulan, wisata_id, COUNT(*) as jumlah')
            ->whereYear('visited_at', $tahun)
            ->with('wisata:id,nama')
            ->groupBy('bulan', 'wisata_id')
            ->orderBy('bulan', 'asc')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->groupBy('bulan')
            ->map(function ($items) {
                return $items->take(5)->map(function ($item) {
                    return [
                        'nama' => $item->wisata->nama ?? 'N/A',
                        'jumlah' => $item->jumlah
                    ];
                });
            });

        return response()->json([
            'data' => $wisataPerBulan,
            'details' => $wisataDetailPerBulan
        ]);
    }
}
