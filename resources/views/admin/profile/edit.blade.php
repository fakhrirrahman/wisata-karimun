@extends('layouts.admin')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil Saya')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Form -->
    <div class="col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama"
                        value="{{ old('nama', $user->nama) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('nama') border-red-500 @enderror"
                        placeholder="Nama lengkap"
                        required
                    >
                    @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Username (Read-only) -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        value="{{ $user->username }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed outline-none"
                        disabled
                    >
                    <p class="text-sm text-gray-500 mt-1">Username tidak dapat diubah</p>
                </div>

                <!-- Foto Profil -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input 
                        type="file" 
                        id="gambar" 
                        name="gambar"
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('gambar') border-red-500 @enderror"
                    >
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                    @error('gambar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Buttons -->
                <div class="flex space-x-4 pt-6 border-t">
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition"
                    >
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a 
                        href="{{ route('dashboard') }}"
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
            <h3 class="font-semibold text-gray-800 mb-4">Pratinjau Profil</h3>
            
            <div class="text-center mb-6">
                @if($user->gambar)
                    <img src="{{ asset('storage/' . $user->gambar) }}" alt="{{ $user->nama }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-blue-500">
                @else
                    <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl mx-auto mb-4">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>

            <div class="space-y-4 text-center">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nama</p>
                    <p class="font-semibold text-gray-800 text-lg">{{ $user->nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Username</p>
                    <p class="text-gray-600">{{ $user->username }}</p>
                </div>
                <div class="pt-4 border-t">
                    <p class="text-xs text-gray-500 uppercase mb-2">Informasi Akun</p>
                    <p class="text-sm text-gray-600">Terdaftar sejak {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
