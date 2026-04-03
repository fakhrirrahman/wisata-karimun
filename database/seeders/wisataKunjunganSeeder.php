<?php

namespace Database\Seeders;

use App\Models\Wisata;
use App\Models\WisataVisit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class wisataKunjunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wisata::create(
            [
                'nama_wisata' => 'Pantai Tanjung Balai Karimun',
                'kategori' => 'Wisata Bahari',
                'kecamatan' => 'KARIMUN',
                'desa' => 'Tanjung Balai Karimun',
                'latitude' => '-0.438889',
                'longitude' => '103.433333',
                'deskripsi' => 'Pantai Tanjung Balai Karimun adalah pantai yang terletak di Tanjung Balai Karimun, Karimun, Kepulauan Riau. Pantai ini memiliki pasir putih dan air yang jernih. Pantai ini juga memiliki fasilitas seperti toilet, mushola, dan tempat parkir.',
                'fasilitas' => 'Toilet, Mushola, Tempat Parkir',
                'gambar' => '',
                'harga' => '10000',
            ]

        );
        // WisataVisit::create(
        //     [
        //         'wisata_id' => 1,
        //         'user_id' => 1,
        //         'ip_address' => '[IP_ADDRESS]',
        //         'visited_at' => '2022-01-01',
        //     ],

        // );
    }
}
