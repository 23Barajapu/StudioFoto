<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class NewPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Silver',
                'description' => 'Paket foto studio dasar',
                'price' => 500000,
                'duration_hours' => 2,
                'photo_count' => 20,
                'edited_photo_count' => 10,
                'include_makeup' => true,
                'include_outfit' => false,
                'features' => json_encode(['Background standar', 'Cetak 5 foto'])
            ],
            [
                'name' => 'Paket Gold',
                'description' => 'Paket foto studio premium',
                'price' => 800000,
                'duration_hours' => 3,
                'photo_count' => 30,
                'edited_photo_count' => 15,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode(['Background premium', 'Cetak 10 foto', 'Album digital'])
            ],
            [
                'name' => 'Paket Platinum',
                'description' => 'Paket foto studio eksklusif',
                'price' => 1200000,
                'duration_hours' => 4,
                'photo_count' => 40,
                'edited_photo_count' => 20,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode(['Background eksklusif', 'Cetak 15 foto', 'Album fisik', 'Frame kayu'])
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
