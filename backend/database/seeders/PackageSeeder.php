<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic Package',
                'slug' => 'basic-package',
                'description' => 'Paket foto studio dasar untuk kebutuhan sederhana. Cocok untuk foto profil, foto keluarga, atau dokumentasi pribadi.',
                'price' => 500000,
                'duration_hours' => 2,
                'photo_count' => 50,
                'edited_photo_count' => 10,
                'include_makeup' => false,
                'include_outfit' => false,
                'features' => json_encode([
                    '2 jam sesi foto',
                    '50 foto mentah',
                    '10 foto hasil edit',
                    '2 background pilihan',
                    'Soft file via Google Drive',
                ]),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Standard Package',
                'slug' => 'standard-package',
                'description' => 'Paket foto studio standar dengan fasilitas lengkap. Ideal untuk acara wisuda, lamaran, atau foto profesional.',
                'price' => 1000000,
                'duration_hours' => 3,
                'photo_count' => 100,
                'edited_photo_count' => 25,
                'include_makeup' => true,
                'include_outfit' => false,
                'features' => json_encode([
                    '3 jam sesi foto',
                    '100 foto mentah',
                    '25 foto hasil edit premium',
                    '4 background pilihan',
                    'Include makeup',
                    'Soft file + 1 cetak 8R',
                    'Free frame foto',
                ]),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium Package',
                'slug' => 'premium-package',
                'description' => 'Paket foto studio premium dengan fasilitas terlengkap. Sempurna untuk prewedding, maternity, atau acara special.',
                'price' => 2000000,
                'duration_hours' => 5,
                'photo_count' => 200,
                'edited_photo_count' => 50,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode([
                    '5 jam sesi foto',
                    '200 foto mentah',
                    '50 foto hasil edit premium',
                    'Unlimited background',
                    'Include makeup professional',
                    'Include 2 setelan outfit',
                    'Soft file + Album 10 halaman',
                    '3 cetak ukuran 8R + frame',
                    'Free video behind the scene',
                ]),
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Wedding Package',
                'slug' => 'wedding-package',
                'description' => 'Paket khusus dokumentasi pernikahan. Meliputi sesi foto prewedding dan hari H dengan tim profesional.',
                'price' => 5000000,
                'duration_hours' => 10,
                'photo_count' => 500,
                'edited_photo_count' => 100,
                'include_makeup' => true,
                'include_outfit' => true,
                'features' => json_encode([
                    'Full day coverage (10 jam)',
                    '500+ foto dokumentasi',
                    '100 foto hasil edit terbaik',
                    '2 fotografer profesional',
                    'Include makeup & hairdo',
                    'Sewa outfit prewedding',
                    'Album eksklusif 20 halaman',
                    'Video cinematic 5-7 menit',
                    'Free cetak foto 10R (10 pcs)',
                    'Online gallery',
                ]),
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $this->command->info('Packages created successfully!');
    }
}
