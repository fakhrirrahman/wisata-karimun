<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\WisataVisit;
use Illuminate\Support\Facades\DB;

class WisataKunjunganController extends Controller
{
    /**
     * Tampilkan daftar kunjungan wisata
     */
    public function index()
    {
        // Ambil semua wisata dengan statistik kunjungan
        $wisata = Wisata::select('id', 'nama', 'kategori', 'visits', 'last_visited_at')
            ->orderByDesc('visits')
            ->paginate(15);

        return view('admin.kunjungan.index', compact('wisata'));
    }

    /**
     * Tampilkan detail kunjungan per wisata
     */
    public function show($id)
    {
        $wisata = Wisata::findOrFail($id);
        
        // Ambil history kunjungan dengan pagination
        $visits = WisataVisit::where('wisata_id', $id)
            ->with('user:id,nama,username')
            ->orderByDesc('visited_at')
            ->paginate(20);

        // Statistik kunjungan
        $stats = [
            'total_visits' => $wisata->visits,
            'last_visited' => $wisata->last_visited_at,
            'visits_today' => WisataVisit::where('wisata_id', $id)
                ->whereDate('visited_at', today())
                ->count(),
            'visits_this_week' => WisataVisit::where('wisata_id', $id)
                ->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'visits_this_month' => WisataVisit::where('wisata_id', $id)
                ->whereMonth('visited_at', now()->month)
                ->whereYear('visited_at', now()->year)
                ->count(),
        ];

        return view('admin.kunjungan.show', compact('wisata', 'visits', 'stats'));
    }

    /**
     * Tampilkan statistik kunjungan per kategori
     */
    public function statistik()
    {
        $normalisasiKategori = "
            CASE
                WHEN kategori IN ('Alam', 'Wisata Alam') THEN 'Wisata Alam'
                WHEN kategori IN ('Bahari', 'Wisata Bahari') THEN 'Wisata Bahari'
                WHEN kategori IN ('Buatan', 'Wisata Buatan') THEN 'Wisata Buatan'
                WHEN kategori IN ('Belanja', 'Wisata Belanja') THEN 'Wisata Belanja'
                WHEN kategori IN ('Heritage', 'Wisata Heritage') THEN 'Wisata Heritage'
                WHEN kategori IN ('Sejarah', 'Wisata Sejarah') THEN 'Wisata Sejarah'
                WHEN kategori IN ('Budaya', 'Wisata Budaya') THEN 'Wisata Budaya'
                WHEN kategori IN ('Kuliner', 'Wisata Kuliner') THEN 'Wisata Kuliner'
                ELSE kategori
            END
        ";

        $kategoriStats = Wisata::select(
                DB::raw("($normalisasiKategori) as kategori"),
                DB::raw('SUM(visits) as total_visits, COUNT(*) as jumlah_wisata')
            )
            ->groupByRaw($normalisasiKategori)
            ->orderByDesc('total_visits')
            ->get();

        $topWisata = Wisata::select('nama', DB::raw("($normalisasiKategori) as kategori"), 'visits')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        return view('admin.kunjungan.statistik', compact('kategoriStats', 'topWisata'));
    }

    /**
     * Reset visits untuk wisata tertentu
     */
    public function resetVisits($id)
    {
        $wisata = Wisata::findOrFail($id);
        $wisata->update([
            'visits' => 0,
            'last_visited_at' => null,
        ]);
        
        WisataVisit::where('wisata_id', $id)->delete();

        return redirect()->back()->with('success', 'Data kunjungan berhasil direset!');
    }
}
