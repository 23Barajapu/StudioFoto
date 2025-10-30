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
                'name' => 'Self Photo Studio',
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
                'name' => 'Keluarga',
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
                'name' => 'Maternity',
                'description' => 'Paket foto studio eksklusif',
                'price' => 1200000,
                'duration_hours' => 4,
                'photo_count' => 40,
                'edited_photo_count' => 20,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode(['Background eksklusif', 'Cetak 15 foto', 'Album fisik', 'Frame kayu'])
            ],
            [
                'name' => 'Prewedding',
                'description' => 'Paket foto studio eksklusif',
                'price' => 1500000,
                'duration_hours' => 5,
                'photo_count' => 50,
                'edited_photo_count' => 25,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode(['Background eksklusif', 'Cetak 20 foto', 'Album premium', 'Frame kayu'])
            ],
            [
                'name' => 'Grup',
                'description' => 'Paket foto studio eksklusif',
                'price' => 2000000,
                'duration_hours' => 6,
                'photo_count' => 60,
                'edited_photo_count' => 30,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode(['Background eksklusif', 'Cetak 30 foto', 'Album grup', 'Frame kayu'])
            ],
            [
                'name' => 'Pas Foto',
                'description' => 'Paket foto studio eksklusif',
                'price' => 300000,
                'duration_hours' => 1,
                'photo_count' => 10,
                'edited_photo_count' => 5,
                'include_makeup' => false,
                'include_outfit' => false,
                'features' => json_encode(['Background standar', 'Cetak 5 foto'])
            ],
            [
                'name' => 'Profile',
                'description' => 'Paket foto studio eksklusif',
                'price' => 400000,
                'duration_hours' => 1,
                'photo_count' => 15,
                'edited_photo_count' => 7,
                'include_makeup' => true,
                'include_outfit' => false,
                'features' => json_encode(['Background standar', 'Cetak 7 foto'])
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
