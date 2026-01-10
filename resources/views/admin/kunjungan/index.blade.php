@extends('layouts.admin')

@section('title', 'Data Kunjungan Wisata')
@section('page-title', 'Kelola Data Kunjungan')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Statistik Kunjungan Wisata</h3>
        <a href="{{ route('kunjungan.statistik') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-chart-pie mr-2"></i>Analisis Statistik
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Total Kunjungan</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $wisata->sum('visits') }}
                    </p>
                </div>
                <i class="fas fa-eye text-blue-600 text-2xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Wisata Terpopuler</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $wisata->where('visits', '>', 0)->count() }}
                    </p>
                </div>
                <i class="fas fa-star text-green-600 text-2xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Rata-rata Kunjungan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">
                        {{ $wisata->count() > 0 ? round($wisata->sum('visits') / $wisata->count()) : 0 }}
                    </p>
                </div>
                <i class="fas fa-chart-bar text-yellow-600 text-2xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Belum Dikunjungi</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">
                        {{ $wisata->where('visits', 0)->count() }}
                    </p>
                </div>
                <i class="fas fa-inbox text-red-600 text-2xl opacity-20"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Kunjungan Wisata -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Kunjungan Wisata</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama Wisata</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kategori</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-800">Total Kunjungan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kunjungan Terakhir</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($wisata as $key => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ ($wisata->currentPage() - 1) * 15 + $key + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="font-bold text-lg text-gray-800">{{ $item->visits }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($item->last_visited_at)
                                {{ $item->last_visited_at->format('d M Y H:i') }}
                            @else
                                <span class="text-gray-400">Belum pernah</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($item->visits > 500)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    🔥 Sangat Sering
                                </span>
                            @elseif($item->visits > 250)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    🌟 Sering
                                </span>
                            @elseif($item->visits > 100)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    👍 Sedang
                                </span>
                            @elseif($item->visits > 10)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                                    📍 Jarang
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    ❄️ Belum/Sangat Jarang
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2 flex">
                            <a href="{{ route('kunjungan.show', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('kunjungan.reset', $item->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin mereset data kunjungan?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition" title="Reset Kunjungan">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl opacity-20 mb-4 block"></i>
                            Belum ada data kunjungan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($wisata->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $wisata->links() }}
        </div>
    @endif
</div>

@endsection
