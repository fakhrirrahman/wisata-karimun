@extends('layouts.admin')

@section('title', 'Edit Lokasi Terdekat')
@section('page-title', 'Edit Lokasi Terdekat')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Wisata: {{ $wisata->nama }}</h3>
        <p class="text-sm text-gray-500">Ubah data lokasi terdekat yang akan muncul di detail wisata.</p>
    </div>

    <form method="POST" action="{{ route('datawisata.nearby.update', [$wisata->id, $nearbyPlace->id]) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lokasi *</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $nearbyPlace->nama) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('nama') border-red-500 @enderror"
                    placeholder="Contoh: Hotel Karimun"
                    required
                >
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                <select
                    id="kategori"
                    name="kategori"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('kategori') border-red-500 @enderror"
                    required
                >
                    <option value="">Pilih kategori</option>
                    <option value="hotel" {{ old('kategori', $nearbyPlace->kategori) == 'hotel' ? 'selected' : '' }}>Hotel</option>
                    <option value="restaurant" {{ old('kategori', $nearbyPlace->kategori) == 'restaurant' ? 'selected' : '' }}>Tempat Makan</option>
                    <option value="service" {{ old('kategori', $nearbyPlace->kategori) == 'service' ? 'selected' : '' }}>Layanan</option>
                    <option value="other" {{ old('kategori', $nearbyPlace->kategori) == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input
                    type="text"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude', $nearbyPlace->latitude) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('latitude') border-red-500 @enderror"
                    placeholder="Contoh: 0.999999"
                >
                @error('latitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input
                    type="text"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude', $nearbyPlace->longitude) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('longitude') border-red-500 @enderror"
                    placeholder="Contoh: 103.999999"
                >
                @error('longitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
            <textarea
                id="alamat"
                name="alamat"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('alamat') border-red-500 @enderror"
                placeholder="Alamat singkat lokasi"
            >{{ old('alamat', $nearbyPlace->alamat) }}</textarea>
            @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex space-x-4 pt-6 border-t">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Update
            </button>
            <a href="{{ route('datawisata.nearby.index', $wisata->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection
