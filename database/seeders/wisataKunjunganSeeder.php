<?php

namespace Database\Seeders;

use App\Models\Wisata;
use App\Models\User;
use App\Models\WisataVisit;
use Illuminate\Database\Seeder;

class wisataKunjunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = $this->loadDistrictCountsFromGeoJson();
        $userIds = User::pluck('id')->all();

        Wisata::query()->delete();

        $kategoriList = [
            'Wisata Bahari',
            'Wisata Alam',
            'Wisata Sejarah',
            'Wisata Budaya',
        ];

        $fasilitasPool = [
            'Area Parkir',
            'Toilet',
            'Mushola',
            'Warung',
            'Gazebo',
            'Spot Foto',
            'Akses Kendaraan',
            'Pusat Informasi',
        ];

        foreach ($districts as $districtName => $districtData) {
            $totalWisata = max(0, (int) ($districtData['count'] ?? 0));

            for ($i = 1; $i <= $totalWisata; $i++) {
                $kategori = $kategoriList[array_rand($kategoriList)];
                $coordinates = $districtData['coordinates'][array_rand($districtData['coordinates'])] ?? [103.4, -0.4];

                $visits = rand(10, 180);
                $lastVisitedAt = now()->subDays(rand(0, 60))->subHours(rand(0, 23));

                $wisata = Wisata::create([
                    'nama' => sprintf('%s %s %02d', $this->kategoriPrefix($kategori), ucwords(strtolower($districtName)), $i),
                    'deskripsi' => sprintf(
                        'Destinasi %s di Kecamatan %s, Kabupaten Karimun, Kepulauan Riau.',
                        strtolower($kategori),
                        ucwords(strtolower($districtName))
                    ),
                    'kategori' => $kategori,
                    'kecamatan' => $districtName,
                    'alamat' => sprintf('Kecamatan %s, Kabupaten Karimun', ucwords(strtolower($districtName))),
                    'latitude' => (string) $coordinates[1],
                    'longitude' => (string) $coordinates[0],
                    'harga' => rand(5000, 25000),
                    'fasilitas' => $this->randomFasilitas($fasilitasPool),
                    'gambar' => null,
                    'visits' => $visits,
                    'last_visited_at' => $lastVisitedAt,
                ]);

                $historyCount = min($visits, rand(5, 30));

                for ($j = 0; $j < $historyCount; $j++) {
                    WisataVisit::create([
                        'wisata_id' => $wisata->id,
                        'user_id' => !empty($userIds) && rand(0, 1) === 1 ? $userIds[array_rand($userIds)] : null,
                        'ip_address' => fake()->ipv4(),
                        'visited_at' => now()
                            ->subDays(rand(0, 90))
                            ->subHours(rand(0, 23))
                            ->subMinutes(rand(0, 59)),
                    ]);
                }
            }
        }

        $this->command?->info('Seeder wisata berbasis GeoJSON berhasil dijalankan.');
    }

    /**
     * Ambil jumlah wisata per kecamatan dari file GeoJSON.
     */
    private function loadDistrictCountsFromGeoJson(): array
    {
        $path = public_path('geojson/jml_wisata.geojson');

        if (!is_file($path)) {
            return [];
        }

        $raw = file_get_contents($path);
        $geoJson = json_decode($raw ?: '{}', true);
        $features = $geoJson['features'] ?? [];

        $districts = [];

        foreach ($features as $feature) {
            $properties = $feature['properties'] ?? [];
            $districtName = strtoupper(trim((string) ($properties['NAMOBJ'] ?? '')));

            if ($districtName === '') {
                continue;
            }

            $count = (int) ($properties['jml_wisata'] ?? 0);
            $point = $this->extractPointFromGeometry($feature['geometry'] ?? []);

            if (!isset($districts[$districtName])) {
                $districts[$districtName] = [
                    'count' => $count,
                    'coordinates' => [],
                ];
            }

            $districts[$districtName]['count'] = max($districts[$districtName]['count'], $count);

            if ($point !== null) {
                $districts[$districtName]['coordinates'][] = $point;
            }
        }

        return $districts;
    }

    /**
     * Ambil 3-5 fasilitas acak untuk tiap wisata.
     */
    private function randomFasilitas(array $pool): array
    {
        shuffle($pool);

        return array_slice($pool, 0, rand(3, 5));
    }

    /**
     * Titik representatif dari geometri Polygon/MultiPolygon.
     */
    private function extractPointFromGeometry(array $geometry): ?array
    {
        $type = $geometry['type'] ?? null;
        $coordinates = $geometry['coordinates'] ?? [];

        if ($type === 'Polygon') {
            return $coordinates[0][0] ?? null;
        }

        if ($type === 'MultiPolygon') {
            return $coordinates[0][0][0] ?? null;
        }

        return null;
    }

    private function kategoriPrefix(string $kategori): string
    {
        return match ($kategori) {
            'Wisata Bahari' => 'Pantai',
            'Wisata Alam' => 'Taman',
            'Wisata Sejarah' => 'Situs',
            'Wisata Budaya' => 'Kampung',
            default => 'Destinasi',
        };
    }
}
