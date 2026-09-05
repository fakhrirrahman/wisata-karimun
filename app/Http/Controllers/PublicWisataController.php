<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\WisataVisit;
use App\Models\WisataReview;

class PublicWisataController extends Controller
{
    public function index()
    {
        $totalWisata = Wisata::count();
        $totalKecamatan = Wisata::whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->distinct('kecamatan')
            ->count('kecamatan');
        $totalKategori = Wisata::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct('kategori')
            ->count('kategori');

        $wisata = Wisata::select('id', 'nama', 'alamat', 'kategori', 'gambar')
            ->orderBy('nama', 'asc')
            ->paginate(8);

        $mostViewedWisata = Wisata::select('id', 'nama', 'alamat', 'kategori', 'gambar', 'visits')
            ->orderByDesc('visits')
            ->orderBy('nama', 'asc')
            ->limit(6)
            ->get();

        return view('public.beranda', compact('wisata', 'totalWisata', 'totalKecamatan', 'totalKategori', 'mostViewedWisata'));
    }

    public function storeUlasan(Request $request)
    {
        $data = $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'nama_pengulas' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:10|max:1000',
        ]);

        WisataReview::create($data);

        return redirect()
            ->route('detail', $data['wisata_id'])
            ->withFragment('ulasan')
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    public function detail($id)
    {
        $wisata = Wisata::with('nearbyPlaces')->findOrFail($id);
        
        // Track visit
        WisataVisit::create([
            'wisata_id' => $wisata->id,
            'user_id' => null,
            'ip_address' => request()->ip(),
            'visited_at' => now(),
        ]);

        // Increment visit counter
        $wisata->incrementVisit();

        $nearbyPlaces = $wisata->nearbyPlaces
            ->map(function ($place) use ($wisata) {
                $placeLat = is_numeric($place->latitude) ? (float) $place->latitude : null;
                $placeLng = is_numeric($place->longitude) ? (float) $place->longitude : null;

                if ($placeLat !== null && $placeLng !== null) {
                    $place->distance_km = $this->haversineKm(
                        (float) $wisata->latitude,
                        (float) $wisata->longitude,
                        $placeLat,
                        $placeLng
                    );
                } else {
                    $place->distance_km = null;
                }

                return $place;
            })
            ->sortBy(function ($place) {
                return $place->distance_km ?? 999999;
            })
            ->values();

        $ulasanWisata = WisataReview::where('wisata_id', $wisata->id)
            ->latest()
            ->paginate(6, ['*'], 'ulasan_page');

        $rataRatingWisata = (float) WisataReview::where('wisata_id', $wisata->id)->avg('rating');

        $totalUlasanWisata = WisataReview::where('wisata_id', $wisata->id)->count();

        return view('public.detail', compact('wisata', 'nearbyPlaces', 'ulasanWisata', 'rataRatingWisata', 'totalUlasanWisata'));
    }

    private function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $toRad = M_PI / 180;
        $dLat = ($lat2 - $lat1) * $toRad;
        $dLon = ($lon2 - $lon1) * $toRad;
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos($lat1 * $toRad) * cos($lat2 * $toRad)
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return 6371 * $c;
    }

    public function peta(Request $request)
    {
        $selectedKecamatans = $request->input('kecamatan', []);
        
        // Filter wisata berdasarkan kecamatan jika ada yang dipilih
        $query = Wisata::query();
        
        if (!empty($selectedKecamatans) && !in_array('all', $selectedKecamatans)) {
            $query->whereIn('kecamatan', $selectedKecamatans);
        }
        
        $wisata = $query->get();

        // Hitung total kunjungan per kecamatan langsung dari database untuk popup batas.
        $jumlahKunjunganPerKecamatan = \App\Models\Wisata::selectRaw('UPPER(kecamatan) as kec, sum(visits) as total')
            ->groupBy('kecamatan')
            ->pluck('total', 'kec')
            ->toArray();

        $heatmapKunjunganPoints = $this->getHeatmapKunjunganPoints();

        return view('public.peta', compact('wisata', 'selectedKecamatans', 'jumlahKunjunganPerKecamatan', 'heatmapKunjunganPoints'));
    }

    public function apiGetAllWisata()
    {
        $wisata = Wisata::select('id', 'nama', 'kategori', 'latitude', 'longitude', 'fasilitas')
            ->get();

        return response()->json($wisata);
    }

    public function apiGetHeatmapKunjungan(Request $request)
    {
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', 'all');

        if ($bulan === 'all' && $tahun === 'all') {
            $data = \App\Models\Wisata::selectRaw('UPPER(kecamatan) as kec, sum(visits) as total')
                ->groupBy('kecamatan')
                ->pluck('total', 'kec')
                ->toArray();
        } else {
            $visitQuery = \App\Models\WisataVisit::join('wisata', 'wisata_visits.wisata_id', '=', 'wisata.id')
                ->selectRaw('UPPER(wisata.kecamatan) as kec, count(wisata_visits.id) as total');

            if ($bulan !== 'all') {
                $visitQuery->whereMonth('wisata_visits.visited_at', $bulan);
            }
            if ($tahun !== 'all') {
                $visitQuery->whereYear('wisata_visits.visited_at', $tahun);
            }

            $data = $visitQuery->groupBy('wisata.kecamatan')
                ->pluck('total', 'kec')
                ->toArray();
        }

        return response()->json($data);
    }

    public function apiGetHeatmapKunjunganPoints(Request $request)
    {
        return response()->json($this->getHeatmapKunjunganPoints(
            $request->input('bulan', 'all'),
            $request->input('tahun', 'all')
        ));
    }

    private function getHeatmapKunjunganPoints(string $bulan = 'all', string $tahun = 'all')
    {
        if ($bulan === 'all' && $tahun === 'all') {
            return Wisata::select('id', 'nama', 'kategori', 'kecamatan', 'latitude', 'longitude')
                ->selectRaw('visits as total')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('latitude', '!=', '')
                ->where('longitude', '!=', '')
                ->get();
        }

        $visitQuery = Wisata::leftJoin('wisata_visits', 'wisata.id', '=', 'wisata_visits.wisata_id')
            ->select(
                'wisata.id',
                'wisata.nama',
                'wisata.kategori',
                'wisata.kecamatan',
                'wisata.latitude',
                'wisata.longitude'
            )
            ->whereNotNull('wisata.latitude')
            ->whereNotNull('wisata.longitude')
            ->where('wisata.latitude', '!=', '')
            ->where('wisata.longitude', '!=', '');

        if ($bulan !== 'all') {
            $visitQuery->whereMonth('wisata_visits.visited_at', $bulan);
        }

        if ($tahun !== 'all') {
            $visitQuery->whereYear('wisata_visits.visited_at', $tahun);
        }

        return $visitQuery
            ->selectRaw('count(wisata_visits.id) as total')
            ->groupBy('wisata.id', 'wisata.nama', 'wisata.kategori', 'wisata.kecamatan', 'wisata.latitude', 'wisata.longitude')
            ->get();
    }
}
