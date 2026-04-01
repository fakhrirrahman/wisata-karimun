@extends('layouts.admin')

@section('title', 'Lokasi Terdekat')
@section('page-title', 'Kelola Lokasi Terdekat')

@section('content')
<div class="mb-6 flex flex-wrap justify-between items-center gap-3">
    <div>
        <h3 class="text-xl font-semibold text-gray-800">{{ $wisata->nama }}</h3>
        <p class="text-sm text-gray-500">Input manual hotel, tempat makan, dan layanan sekitar wisata.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('datawisata.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <a href="{{ route('datawisata.nearby.create', $wisata->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Lokasi
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Alamat</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Koordinat</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($nearbyPlaces as $key => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $key + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item->kategori_label }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->alamat ?: '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->latitude && $item->longitude ? $item->latitude . ', ' . $item->longitude : '-' }}</td>
                        <td class="px-6 py-4 text-sm space-x-2 flex">
                            <a href="{{ route('datawisata.nearby.edit', [$wisata->id, $item->id]) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('datawisata.nearby.destroy', [$wisata->id, $item->id]) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-location-dot text-4xl opacity-20 mb-4 block"></i>
                            Belum ada lokasi terdekat untuk wisata ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
