@extends('layouts.admin')

@section('title', 'Detail Kunjungan - ' . $wisata->nama)
@section('page-title', 'Detail Kunjungan Wisata')

@section('content')
<!-- Back Button & Title -->
<div class="mb-6">
    <a href="{{ route('kunjungan.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <h3 class="text-2xl font-bold text-gray-800">{{ $wisata->nama }}</h3>
    <p class="text-gray-600 text-sm mt-1">Kategori: <span class="font-semibold">{{ $wisata->kategori }}</span></p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
        <p class="text-gray-600 text-sm uppercase font-semibold">Total Kunjungan</p>
        <p class="text-4xl font-bold text-blue-600 mt-3">{{ $stats['total_visits'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
        <p class="text-gray-600 text-sm uppercase font-semibold">Kunjungan Hari Ini</p>
        <p class="text-4xl font-bold text-green-600 mt-3">{{ $stats['visits_today'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
        <p class="text-gray-600 text-sm uppercase font-semibold">Minggu Ini</p>
        <p class="text-4xl font-bold text-purple-600 mt-3">{{ $stats['visits_this_week'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-600">
        <p class="text-gray-600 text-sm uppercase font-semibold">Bulan Ini</p>
        <p class="text-4xl font-bold text-orange-600 mt-3">{{ $stats['visits_this_month'] }}</p>
    </div>
</div>

<!-- Info Card -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Wisata</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-gray-600 text-sm">Status Kunjungan</p>
            @if($stats['total_visits'] > 500)
                <p class="text-red-600 font-bold text-lg mt-1">🔥 Sangat Sering Dikunjungi</p>
            @elseif($stats['total_visits'] > 250)
                <p class="text-orange-600 font-bold text-lg mt-1">🌟 Sering Dikunjungi</p>
            @elseif($stats['total_visits'] > 100)
                <p class="text-yellow-600 font-bold text-lg mt-1">👍 Sedang Dikunjungi</p>
            @elseif($stats['total_visits'] > 10)
                <p class="text-pink-600 font-bold text-lg mt-1">📍 Jarang Dikunjungi</p>
            @else
                <p class="text-gray-600 font-bold text-lg mt-1">❄️ Belum/Sangat Jarang Dikunjungi</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Kunjungan Terakhir</p>
            @if($stats['last_visited'])
                <p class="font-semibold text-gray-800 mt-1">{{ $stats['last_visited']->format('d M Y pukul H:i') }}</p>
            @else
                <p class="text-gray-600 italic mt-1">Belum pernah dikunjungi</p>
            @endif
        </div>
    </div>
</div>

<!-- Tabel History Kunjungan -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h4 class="text-lg font-semibold text-gray-800">Riwayat Kunjungan (20 Terakhir)</h4>
        @if($stats['total_visits'] > 0)
            <form method="POST" action="{{ route('kunjungan.reset', $wisata->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin mereset semua data kunjungan untuk wisata ini?');">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm transition">
                    <i class="fas fa-redo mr-2"></i>Reset Data
                </button>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Waktu Kunjungan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($visits as $key => $visit)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ ($visits->currentPage() - 1) * 20 + $key + 1 }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-gray-800">{{ $visit->visited_at->format('d M Y') }}</span>
                            <span class="text-gray-600 text-xs block mt-1">{{ $visit->visited_at->format('H:i:s') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($visit->user_id)
                                <span class="text-gray-800">User ID: {{ $visit->user_id }}</span>
                            @else
                                <span class="text-gray-400 italic">Pengunjung Anonim</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $visit->ip_address ?? '-' }}</code>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl opacity-20 mb-4 block"></i>
                            Belum ada riwayat kunjungan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($visits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $visits->links() }}
        </div>
    @endif
</div>

@endsection
