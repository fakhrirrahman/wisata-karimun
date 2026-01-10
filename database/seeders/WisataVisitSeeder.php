<?php

namespace Database\Seeders;

use App\Models\Wisata;
use App\Models\WisataVisit;
use App\Models\User;
use Illuminate\Database\Seeder;

class WisataVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua wisata dan user
        $wisataList = Wisata::all();
        $userIds = User::pluck('id')->toArray();

        foreach ($wisataList as $wisata) {
            // Generate random visits antara 0 hingga 600
            $totalVisits = rand(0, 600);
            
            // Update wisata dengan jumlah visits
            $wisata->update([
                'visits' => $totalVisits,
                'last_visited_at' => $totalVisits > 0 ? now()->subDays(rand(0, 30)) : null,
            ]);

            // Buat dummy visit records untuk history
            for ($i = 0; $i < $totalVisits; $i++) {
                $userId = null;
                // Jika ada user, 50% chance untuk assign user ID
                if (!empty($userIds) && rand(0, 1) === 1) {
                    $userId = $userIds[array_rand($userIds)];
                }

                WisataVisit::create([
                    'wisata_id' => $wisata->id,
                    'user_id' => $userId,
                    'ip_address' => fake()->ipv4(),
                    'visited_at' => now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);
            }
        }

        $this->command->info('✅ Wisata visits seeder berhasil dijalankan!');
    }
}

