<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataNearbyPlaceController extends Controller
{
    public function index($wisataId)
    {
        $wisata = Wisata::findOrFail($wisataId);
        $nearbyPlaces = $wisata->nearbyPlaces()->orderBy('kategori')->orderBy('nama')->get();

        return view('admin.lokasi-terdekat.index', compact('wisata', 'nearbyPlaces'));
    }

    public function create($wisataId)
    {
        $wisata = Wisata::findOrFail($wisataId);

        return view('admin.lokasi-terdekat.create', compact('wisata'));
    }

    public function store(Request $request, $wisataId)
    {
        $wisata = Wisata::findOrFail($wisataId);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:hotel,restaurant,service,other',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|regex:/^-?\d+(\.\d+)?$/',
            'longitude' => 'nullable|regex:/^-?\d+(\.\d+)?$/',
        ]);

        $wisata->nearbyPlaces()->create($validated);

        return redirect()
            ->route('datawisata.nearby.index', $wisata->id)
            ->with('success', 'Lokasi terdekat berhasil ditambahkan.');
    }

    public function edit($wisataId, $id)
    {
        $wisata = Wisata::findOrFail($wisataId);
        $nearbyPlace = $wisata->nearbyPlaces()->findOrFail($id);

        return view('admin.lokasi-terdekat.edit', compact('wisata', 'nearbyPlace'));
    }

    public function update(Request $request, $wisataId, $id)
    {
        $wisata = Wisata::findOrFail($wisataId);
        $nearbyPlace = $wisata->nearbyPlaces()->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:hotel,restaurant,service,other',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|regex:/^-?\d+(\.\d+)?$/',
            'longitude' => 'nullable|regex:/^-?\d+(\.\d+)?$/',
        ]);

        $nearbyPlace->update($validated);

        return redirect()
            ->route('datawisata.nearby.index', $wisata->id)
            ->with('success', 'Lokasi terdekat berhasil diperbarui.');
    }

    public function destroy($wisataId, $id)
    {
        $wisata = Wisata::findOrFail($wisataId);
        $nearbyPlace = $wisata->nearbyPlaces()->findOrFail($id);

        $nearbyPlace->delete();

        return redirect()
            ->route('datawisata.nearby.index', $wisata->id)
            ->with('success', 'Lokasi terdekat berhasil dihapus.');
    }
}
