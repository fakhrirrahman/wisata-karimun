<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $kategoriStats = Wisata::select('kategori', DB::raw('SUM(visits) as total_visits, COUNT(*) as jumlah_wisata'))
            ->groupBy('kategori')
            ->orderByDesc('total_visits')
            ->get();

        $topWisata = Wisata::select('nama', 'kategori', 'visits')
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
