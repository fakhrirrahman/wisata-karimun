<?php

namespace Database\Seeders;

use App\Models\WisataAnnualVisit;
use Illuminate\Database\Seeder;

class WisataAnnualVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $annualVisits = [
            2021 => 15,
            2022 => 15750,
            2023 => 58093,
            2024 => 0,
            2025 => 65632,
        ];

        foreach ($annualVisits as $year => $totalVisits) {
            WisataAnnualVisit::updateOrCreate(
                ['year' => $year],
                ['total_visits' => $totalVisits]
            );
        }
    }
}
