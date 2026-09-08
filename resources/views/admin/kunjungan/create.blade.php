@extends('layouts.admin')

@section('title', 'Tambah Data Kunjungan')
@section('page-title', 'Tambah Data Kunjungan')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('kunjungan.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-6">
            <!-- Pilih Wisata -->
            <div class="col-span-2">
                <label for="wisata_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Wisata <span class="text-gray-400 font-normal">(opsional)</span></label>
                <select 
                    id="wisata_id" 
                    name="wisata_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('wisata_id') border-red-500 @enderror"
                >
                    <option value="">Data agregat / tidak spesifik wisata</option>
                    @foreach($wisata as $w)
                        <option value="{{ $w->id }}" {{ old('wisata_id') == $w->id ? 'selected' : '' }}>{{ $w->nama }}</option>
                    @endforeach
                </select>
                @error('wisata_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="mt-2 text-sm text-gray-500"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Kosongkan untuk mengisi total kunjungan tahunan seluruh Karimun pada menu Peta Kunjungan.</p>
            </div>

            <!-- Bulan -->
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan <span class="text-gray-400 font-normal">(opsional)</span></label>
                <select 
                    id="bulan" 
                    name="bulan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('bulan') border-red-500 @enderror"
                >
                    <option value="">Data Tahunan</option>
                    @php
                        $bulans = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($bulans as $num => $name)
                        <option value="{{ $num }}" {{ old('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('bulan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="mt-2 text-sm text-gray-500"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Pilih Data Tahunan untuk peta kunjungan seluruh Karimun.</p>
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun *</label>
                <input 
                    type="number" 
                    id="tahun" 
                    name="tahun"
                    value="{{ old('tahun', date('Y')) }}"
                    min="2000" max="{{ date('Y') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('tahun') border-red-500 @enderror"
                    required
                >
                @error('tahun')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Jumlah Kunjungan -->
            <div class="col-span-2">
                <label for="jumlah_kunjungan" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kunjungan *</label>
                <input 
                    type="number" 
                    id="jumlah_kunjungan" 
                    name="jumlah_kunjungan"
                    value="{{ old('jumlah_kunjungan') }}"
                    min="0" max="1000000"
                    placeholder="Contoh: 15750"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none @error('jumlah_kunjungan') border-red-500 @enderror"
                    required
                >
                @error('jumlah_kunjungan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="mt-2 text-sm text-gray-500"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Jika wisata dikosongkan dan bulan Data Tahunan, data dipakai untuk Peta Kunjungan. Jika wisata dipilih, data masuk ke kunjungan lokasi wisata.</p>
            </div>
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
                href="{{ route('kunjungan.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition"
            >
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Styling select2 agar pas dengan desain Tailwind */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border-color: #d1d5db !important;
        border-radius: 0.5rem !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #374151 !important;
        padding-left: 0.5rem;
        padding-right: 2rem;
        line-height: normal;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6b7280 !important;
    }
    .select2-search__field {
        outline: none !important;
    }
    .select2-search__field:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 1px #2563eb !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#wisata_id').select2({
            placeholder: "Data agregat / tidak spesifik wisata",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
