<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'username' => 'tes',
        ], [
            'nama' => 'Test User',
            'password' => Hash::make('1'),
        ]);

        $this->call([
            wisataKunjunganSeeder::class,
        ]);
    }
}
