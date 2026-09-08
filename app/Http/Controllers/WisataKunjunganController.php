<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\WisataAnnualVisit;
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
     * Tampilkan form tambah kunjungan manual
     */
    public function create()
    {
        $wisata = Wisata::orderBy('nama')->get(['id', 'nama']);
        return view('admin.kunjungan.create', compact('wisata'));
    }

    /**
     * Simpan data kunjungan manual
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'wisata_id' => 'nullable|exists:wisata,id',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'bulan' => 'nullable|integer|min:1|max:12',
            'jumlah_kunjungan' => 'required|integer|min:0|max:1000000',
        ]);

        $wisata = $request->filled('wisata_id') ? Wisata::findOrFail($request->wisata_id) : null;
        
        $jumlah = $request->jumlah_kunjungan;
        $tahun = $request->tahun;
        $bulan = $request->filled('bulan') ? (int) $request->bulan : null;

        if (!$wisata && !$bulan) {
            WisataAnnualVisit::updateOrCreate(
                ['year' => $tahun],
                ['total_visits' => $jumlah]
            );

            return redirect()
                ->route('kunjungan.index')
                ->with('success', "Data kunjungan tahunan Karimun {$tahun} berhasil disimpan untuk peta kunjungan.");
        }
        
        $visits = [];
        
        for ($i = 0; $i < $jumlah; $i++) {
            $visitMonth = $bulan ?? rand(1, $tahun == date('Y') ? date('n') : 12);
            $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $visitMonth)->daysInMonth;
            // Generate valid random date up to current date if it's the current year and month
            $currentYear = date('Y');
            $currentMonth = date('n');
            $currentDay = date('j');
            
            $maxDay = $daysInMonth;
            if ($tahun == $currentYear && $visitMonth == $currentMonth) {
                $maxDay = $currentDay;
            }
            
            $randomDay = rand(1, $maxDay);
            $randomHour = rand(8, 17);
            $randomMinute = rand(0, 59);
            $randomSecond = rand(0, 59);
            
            $visitedAt = \Carbon\Carbon::create($tahun, $visitMonth, $randomDay, $randomHour, $randomMinute, $randomSecond);
            
            // Prevent future dates
            if ($visitedAt->isFuture()) {
                $visitedAt = now();
            }
            
            $visits[] = [
                'wisata_id' => $wisata?->id,
                'user_id' => null,
                'ip_address' => '127.0.0.1', // Manual entry
                'visited_at' => $visitedAt,
            ];
            
            // Batch insert to avoid memory issues and query limits
            if (count($visits) >= 1000) {
                WisataVisit::insert($visits);
                $visits = [];
            }
        }
        
        if (count($visits) > 0) {
            WisataVisit::insert($visits);
        }
        
        if ($wisata) {
            // Update aggregated visits count hanya untuk data yang punya lokasi wisata.
            $wisata->increment('visits', $jumlah);
            
            // Update last_visited_at
            $latestVisitDate = $bulan
                ? \Carbon\Carbon::create($tahun, $bulan)->endOfMonth()
                : \Carbon\Carbon::create($tahun, 12)->endOfYear();
            if ($latestVisitDate->isFuture()) {
                $latestVisitDate = now();
            }
            
            if (!$wisata->last_visited_at || $latestVisitDate->greaterThan($wisata->last_visited_at)) {
                $wisata->update(['last_visited_at' => $latestVisitDate]);
            }
        }
        
        return redirect()->route('kunjungan.index')->with('success', $wisata
            ? 'Data kunjungan per wisata berhasil ditambahkan.'
            : 'Data kunjungan agregat berhasil ditambahkan.');
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
