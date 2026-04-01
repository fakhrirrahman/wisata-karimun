@extends('layouts.admin')

@section('title', 'Data Wisata')
@section('page-title', 'Kelola Data Wisata')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-semibold text-gray-800">Daftar Wisata</h3>
    <a href="{{ route('datawisata.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Tambah Wisata
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama Wisata</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Lokasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Harga</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-800">Kunjungan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($wisata as $key => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $key + 1 }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-3">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="w-10 h-10 rounded object-cover">
                                @endif
                                <span class="font-medium text-gray-800">{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->alamat, 30) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @if($item->harga)
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <div class="flex items-center justify-center">
                                @if($item->visits > 0)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-lg">
                                        {{ $item->visits }}
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        Belum Dikunjungi
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2 flex">
                            <a href="{{ route('datawisata.nearby.index', $item->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded transition" title="Lokasi Terdekat">
                                <i class="fas fa-location-dot"></i>
                            </a>
                            <a href="{{ route('datawisata.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('kunjungan.show', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition" title="Lihat Kunjungan">
                                <i class="fas fa-chart-line"></i>
                            </a>
                            <form method="POST" action="{{ route('datawisata.destroy', $item->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                            <i class="fas fa-inbox text-4xl opacity-20 mb-4 block"></i>
                            Belum ada data wisata
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
