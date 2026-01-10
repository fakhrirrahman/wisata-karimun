@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Total Wisata</p>
        <p class="text-2xl font-bold text-gray-800">{{ $wisataData->count() }}</p>
        <i class="fas fa-map-marker-alt text-blue-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Kategori</p>
        <p class="text-2xl font-bold text-gray-800">{{ $kategoriCount->count() }}</p>
        <i class="fas fa-th text-green-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Kecamatan</p>
        <p class="text-2xl font-bold text-gray-800">{{ $kecamatanCount->count() }}</p>
        <i class="fas fa-city text-purple-500 text-2xl opacity-20 float-right"></i>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-xs mb-1">Total Harga</p>
        <p class="text-xl font-bold text-gray-800">
            @php
                $totalHarga = $wisataData->sum('harga');
                echo 'Rp ' . number_format($totalHarga, 0, ',', '.');
            @endphp
        </p>
    </div>
</div>

@endsection