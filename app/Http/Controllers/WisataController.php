<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    public function index()
    {
        $wisata = Wisata::orderBy('created_at', 'desc')->get();
        return view('admin.datawisata.index', compact('wisata'));
    }

    public function create()
    {
        return view('admin.datawisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'latitude' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'longitude' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'harga' => 'nullable|numeric',
            'fasilitas' => 'required|array',
            'gambar' => 'required|image|max:2048',
        ]);

        $wisata = new Wisata();
        $wisata->nama = $request->nama;
        $wisata->deskripsi = $request->deskripsi;
        $wisata->kategori = $request->kategori;
        $wisata->kecamatan = $request->kecamatan;
        $wisata->alamat = $request->alamat;
        $wisata->latitude = $request->latitude;
        $wisata->longitude = $request->longitude;
        $wisata->harga = $request->harga;
        $wisata->fasilitas = json_encode($request->fasilitas);

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->storeAs('wisata_images', 
                $request->file('gambar')->getClientOriginalName(), 'public');
            $wisata->gambar = $imagePath;
        }

        $wisata->save();

        return redirect()->route('datawisata.index')->with('success', 'Data Wisata berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $wisata = Wisata::findOrFail($id);
        return view('admin.datawisata.edit', compact('wisata'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'latitude' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'longitude' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'harga' => 'nullable|numeric',
            'fasilitas' => 'required|array',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $wisata = Wisata::findOrFail($id);
        $wisata->nama = $request->nama;
        $wisata->deskripsi = $request->deskripsi;
        $wisata->kategori = $request->kategori;
        $wisata->kecamatan = $request->kecamatan;
        $wisata->alamat = $request->alamat;
        $wisata->latitude = $request->latitude;
        $wisata->longitude = $request->longitude;
        $wisata->harga = $request->harga;
        $wisata->fasilitas = json_encode($request->fasilitas);

        if ($request->hasFile('gambar')) {
            if ($wisata->gambar) {
                Storage::disk('public')->delete($wisata->gambar);
            }
            $imagePath = $request->file('gambar')->storeAs('wisata_images', 
                $request->file('gambar')->getClientOriginalName(), 'public');
            $wisata->gambar = $imagePath;
        }

        $wisata->save();

        return redirect()->route('datawisata.index')->with('success', 'Data Wisata berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $wisata = Wisata::findOrFail($id);

        if ($wisata->gambar) {
            Storage::disk('public')->delete($wisata->gambar);
        }

        $wisata->delete();

        return redirect()->route('datawisata.index')->with('success', 'Data Wisata berhasil dihapus!');
    }
}
