<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\WisataVisit;

class PublicWisataController extends Controller
{
    public function index()
    {
        $wisata = Wisata::select('id', 'nama', 'alamat', 'kategori', 'gambar')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('public.beranda', compact('wisata'));
    }

    public function detail($id)
    {
        $wisata = Wisata::findOrFail($id);
        
        // Track visit
        WisataVisit::create([
            'wisata_id' => $wisata->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'visited_at' => now(),
        ]);

        // Increment visit counter
        $wisata->incrementVisit();

        return view('public.detail', compact('wisata'));
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

        return view('public.peta', compact('wisata', 'selectedKecamatans'));
    }

    public function apiGetAllWisata()
    {
        $wisata = Wisata::select('id', 'nama', 'kategori', 'latitude', 'longitude', 'fasilitas')
            ->get();

        return response()->json($wisata);
    }
}