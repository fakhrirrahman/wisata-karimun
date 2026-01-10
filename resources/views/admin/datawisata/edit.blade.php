@extends('layouts.admin')

@section('title', 'Edit Wisata')
@section('page-title', 'Edit Data Wisata')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Form -->
    <div class="col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('datawisata.update', $wisata->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">
                    <!-- Nama Wisata -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Wisata *</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama"
                            value="{{ old('nama', $wisata->nama) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('nama') border-red-500 @enderror"
                            placeholder="Nama wisata"
                            required
                        >
                        @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select 
                            id="kategori" 
                            name="kategori"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('kategori') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih kategori</option>
                            <option value="Wisata Alam" {{ old('kategori', $wisata->kategori) == 'Wisata Alam' ? 'selected' : '' }}>Wisata Alam</option>
                            <option value="Wisata Budaya" {{ old('kategori', $wisata->kategori) == 'Wisata Budaya' ? 'selected' : '' }}>Wisata Budaya</option>
                            <option value="Wisata Buatan" {{ old('kategori', $wisata->kategori) == 'Wisata Buatan' ? 'selected' : '' }}>Wisata Buatan</option>
                            <option value="Wisata Kuliner" {{ old('kategori', $wisata->kategori) == 'Wisata Kuliner' ? 'selected' : '' }}>Wisata Kuliner</option>
                            <option value="Wisata Kerajinan" {{ old('kategori', $wisata->kategori) == 'Wisata Kerajinan' ? 'selected' : '' }}>Wisata Kerajinan</option>
                        </select>
                        @error('kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude *</label>
                        <input 
                            type="text" 
                            id="latitude" 
                            name="latitude"
                            value="{{ old('latitude', $wisata->latitude) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('latitude') border-red-500 @enderror"
                            placeholder="Latitude"
                            required
                        >
                        @error('latitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude *</label>
                        <input 
                            type="text" 
                            id="longitude" 
                            name="longitude"
                            value="{{ old('longitude', $wisata->longitude) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('longitude') border-red-500 @enderror"
                            placeholder="Longitude"
                            required
                        >
                        @error('longitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga Tiket (Rp)</label>
                        <input 
                            type="number" 
                            id="harga" 
                            name="harga"
                            value="{{ old('harga', $wisata->harga) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none"
                            placeholder="0"
                        >
                    </div>

                    <!-- Gambar -->
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                        <input 
                            type="file" 
                            id="gambar" 
                            name="gambar"
                            accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('gambar') border-red-500 @enderror"
                        >
                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                        @error('gambar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                    <textarea 
                        id="deskripsi" 
                        name="deskripsi"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsi singkat wisata"
                        required
                    >{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                    <textarea 
                        id="alamat" 
                        name="alamat"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('alamat') border-red-500 @enderror"
                        placeholder="Alamat lengkap"
                        required
                    >{{ old('alamat', $wisata->alamat) }}</textarea>
                    @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Fasilitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas *</label>
                    <div class="space-y-2">
                        @php
                            $fasilitas_list = ['Mushola', 'Tempat Makan', 'Toilet', 'Parkir', 'Kamar Bilas', 'Toko Souvenir', 'Pengamanan'];
                            $wisata_fasilitas = is_array($wisata->fasilitas) ? $wisata->fasilitas : [];
                        @endphp
                        @foreach($fasilitas_list as $f)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="fasilitas[]"
                                    value="{{ $f }}"
                                    {{ in_array($f, $wisata_fasilitas) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <span class="ml-2 text-gray-700">{{ $f }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('fasilitas')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Buttons -->
                <div class="flex space-x-4 pt-6 border-t">
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition"
                    >
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                    <a 
                        href="{{ route('datawisata.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition"
                    >
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview -->
    <div class="col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-24">
            <h3 class="font-semibold text-gray-800 mb-4">Preview</h3>
            
            @if($wisata->gambar)
                <img src="{{ asset('storage/' . $wisata->gambar) }}" alt="{{ $wisata->nama }}" class="w-full h-48 object-cover rounded-lg mb-4">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center text-gray-400">
                    <i class="fas fa-image text-4xl"></i>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nama</p>
                    <p class="font-semibold text-gray-800">{{ $wisata->nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Kategori</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $wisata->kategori }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Koordinat</p>
                    <p class="text-sm text-gray-800">{{ $wisata->latitude }}, {{ $wisata->longitude }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Harga</p>
                    <p class="text-sm text-gray-800">
                        @if($wisata->harga)
                            Rp{{ number_format($wisata->harga, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
