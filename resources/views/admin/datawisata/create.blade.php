@extends('layouts.admin')

@section('title', 'Tambah Wisata')
@section('page-title', 'Tambah Data Wisata')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('datawisata.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-6">
            <!-- Nama Wisata -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Wisata *</label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama"
                    value="{{ old('nama') }}"
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
                    <option value="Wisata Alam" {{ old('kategori') == 'Wisata Alam' ? 'selected' : '' }}>Wisata Alam</option>
                    <option value="Wisata Bahari" {{ old('kategori') == 'Wisata Bahari' ? 'selected' : '' }}>Wisata Bahari</option>
                    <option value="Wisata Buatan" {{ old('kategori') == 'Wisata Buatan' ? 'selected' : '' }}>Wisata Buatan</option>
                    <option value="Cagar Budaya" {{ old('kategori') == 'Cagar Budaya' ? 'selected' : '' }}>Cagar Budaya</option>
                    <option value="Wisata Belanja" {{ old('kategori') == 'Wisata Belanja' ? 'selected' : '' }}>Wisata Belanja</option>
                    <option value="Wisata Heritage" {{ old('kategori') == 'Wisata Heritage' ? 'selected' : '' }}>Wisata Heritage</option>
                    <option value="Wisata Sejarah" {{ old('kategori') == 'Wisata Sejarah' ? 'selected' : '' }}>Wisata Sejarah</option>
                    <option value="Wisata Budaya" {{ old('kategori') == 'Wisata Budaya' ? 'selected' : '' }}>Wisata Budaya</option>
                </select>
                @error('kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Kecamatan -->
            <div>
                <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan *</label>
                <select 
                    id="kecamatan" 
                    name="kecamatan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('kecamatan') border-red-500 @enderror"
                    required
                >
                    <option value="">Pilih kecamatan</option>
                    @php
                            $listKec = [
                                'BELAT',
                                'BURU',
                                'DURAI',
                                'KARIMUN',
                                'KUNDUR',
                                'KUNDUR BARAT',
                                'KUNDUR UTARA',
                                'MERAL',
                                'MERAL BARAT',
                                'MORO',
                                'TEBING',
                                'UNGAR'
                            ];                    
                    @endphp
                    @foreach($listKec as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                @error('kecamatan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Latitude -->
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude *</label>
                <input 
                    type="text" 
                    id="latitude" 
                    name="latitude"
                    value="{{ old('latitude') }}"
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
                    value="{{ old('longitude') }}"
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
                    value="{{ old('harga') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none"
                    placeholder="0"
                >
            </div>

            <!-- Gambar -->
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar *</label>
                <input 
                    type="file" 
                    id="gambar" 
                    name="gambar"
                    accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('gambar') border-red-500 @enderror"
                    required
                >
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
            >{{ old('deskripsi') }}</textarea>
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
            >{{ old('alamat') }}</textarea>
            @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Fasilitas -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas *</label>
            <div class="space-y-2">
                @php
                    $fasilitas_list = ['Mushola', 'Tempat Makan', 'Toilet', 'Parkir', 'Kamar Bilas', 'Toko Souvenir', 'Pengamanan'];
                    $old_fasilitas = old('fasilitas') ?? [];
                @endphp
                @foreach($fasilitas_list as $f)
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="fasilitas[]"
                            value="{{ $f }}"
                            {{ in_array($f, $old_fasilitas) ? 'checked' : '' }}
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
                <i class="fas fa-save mr-2"></i>Simpan
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
@endsection
