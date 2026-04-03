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

        $wisata = Wisata::select('id', 'nama', 'alamat', 'kategori', 'gambar')
            ->orderBy('nama', 'asc')
            ->paginate(8);

        $mostViewedWisata = Wisata::select('id', 'nama', 'alamat', 'kategori', 'gambar', 'visits')
            ->orderByDesc('visits')
            ->orderBy('nama', 'asc')
            ->limit(6)
            ->get();

        return view('public.beranda', compact('wisata', 'totalWisata', 'mostViewedWisata'));
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

        // Hitung total wisata per kecamatan langsung dari database
        $jumlahWisataPerKecamatan = \App\Models\Wisata::selectRaw('UPPER(kecamatan) as kec, count(*) as total')
            ->groupBy('kecamatan')
            ->pluck('total', 'kec')
            ->toArray();

        return view('public.peta', compact('wisata', 'selectedKecamatans', 'jumlahWisataPerKecamatan'));
    }

    public function apiGetAllWisata()
    {
        $wisata = Wisata::select('id', 'nama', 'kategori', 'latitude', 'longitude', 'fasilitas')
            ->get();

        return response()->json($wisata);
    }
}