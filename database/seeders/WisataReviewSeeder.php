<?php

namespace Database\Seeders;

use App\Models\Wisata;
use App\Models\WisataReview;
use Illuminate\Database\Seeder;

class WisataReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wisataList = Wisata::select('id')->get();

        if ($wisataList->isEmpty()) {
            $this->command?->warn('Wisata belum ada, seeder ulasan dilewati.');
            return;
        }

        WisataReview::query()->delete();

        $namaPengulas = [
            'Andi Pratama',
            'Siti Rahma',
            'Rudi Hartono',
            'Nabila Putri',
            'Fajar Maulana',
            'Yuni Lestari',
            'Ilham Saputra',
            'Rina Oktavia',
        ];

        $templateUlasan = [
            'Tempatnya bersih dan pemandangannya sangat bagus, cocok untuk keluarga.',
            'Akses jalan mudah dan fasilitas cukup lengkap, akan datang lagi.',
            'Lokasinya nyaman untuk santai sore, suasananya tenang dan asri.',
            'Objek wisata menarik, semoga perawatan fasilitas terus ditingkatkan.',
            'Pelayanan ramah dan area wisata cukup terawat, recommended.',
            'Tempat foto bagus, area parkir luas, cocok untuk akhir pekan.',
        ];

        $inserted = 0;

        foreach ($wisataList as $wisata) {
            $jumlahUlasan = rand(1, 4);

            for ($i = 0; $i < $jumlahUlasan; $i++) {
                WisataReview::create([
                    'wisata_id' => $wisata->id,
                    'nama_pengulas' => $namaPengulas[array_rand($namaPengulas)],
                    'rating' => rand(3, 5),
                    'ulasan' => $templateUlasan[array_rand($templateUlasan)],
                    'created_at' => now()->subDays(rand(0, 45))->subHours(rand(0, 23)),
                    'updated_at' => now(),
                ]);
                $inserted++;
            }
        }

        $this->command?->info("Seeder ulasan berhasil. Total ulasan: {$inserted}");
    }
}