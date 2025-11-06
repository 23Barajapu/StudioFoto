<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create categories
        $categories = [
            'Self Photo' => [
                'description' => 'Sesi foto untuk diri sendiri dengan berbagai tema menarik',
                'packages' => [
                    [
                        'name' => 'Basic', 
                        'price' => 100000, 
                        'duration' => 30, 
                        'description' => "1 Konsep\n5 File Edit\n5 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 5,
                        'features' => [
                            '1 Konsep',
                            '5 File Edit',
                            '5 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ],
                    [
                        'name' => 'Premium', 
                        'price' => 200000, 
                        'duration' => 60, 
                        'description' => "2 Konsep\n10 File Edit\n10 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 10,
                        'features' => [
                            '2 Konsep',
                            '10 File Edit',
                            '10 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ]
                ]
            ],
            'Pas Foto' => [
                'description' => 'Foto resmi untuk keperluan dokumen',
                'packages' => [
                    [
                        'name' => 'Biasa', 
                        'price' => 50000, 
                        'duration' => 15, 
                        'description' => "1 Konsep\n1 File Edit\n4 Lembar Cetak 3x4",
                        'max_photos' => 1,
                        'features' => [
                            '1 Konsep',
                            '1 File Edit',
                            '4 Lembar Cetak 3x4'
                        ]
                    ],
                    [
                        'name' => 'Profesional', 
                        'price' => 100000, 
                        'duration' => 30, 
                        'description' => "2 Konsep\n2 File Edit\n8 Lembar Cetak (4x 3x4, 4x 4x6)",
                        'max_photos' => 2,
                        'features' => [
                            '2 Konsep',
                            '2 File Edit',
                            '8 Lembar Cetak (4x 3x4, 4x 4x6)'
                        ]
                    ]
                ]
            ],
            'Keluarga' => [
                'description' => 'Abadikan momen kebersamaan bersama keluarga tercinta',
                'packages' => [
                    [
                        'name' => 'Kecil (maks 4 orang)', 
                        'price' => 300000, 
                        'duration' => 60, 
                        'description' => "1 Konsep\n10 File Edit\n10 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 10,
                        'features' => [
                            '1 Konsep',
                            '10 File Edit',
                            '10 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ],
                    [
                        'name' => 'Besar (5-8 orang)', 
                        'price' => 500000, 
                        'duration' => 90, 
                        'description' => "2 Konsep\n20 File Edit\n20 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 20,
                        'features' => [
                            '2 Konsep',
                            '20 File Edit',
                            '20 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ]
                ]
            ],
            'Grup' => [
                'description' => 'Foto bersama teman atau rekan kerja dengan konsep yang keren',
                'packages' => [
                    [
                        'name' => 'Kecil (5-10 orang)', 
                        'price' => 1000000, 
                        'duration' => 60, 
                        'description' => "1 Konsep\n15 File Edit\n15 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 15,
                        'features' => [
                            '1 Konsep',
                            '15 File Edit',
                            '15 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ],
                    [
                        'name' => 'Besar (11-20 orang)', 
                        'price' => 1500000, 
                        'duration' => 90, 
                        'description' => "2 Konsep\n25 File Edit\n25 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 25,
                        'features' => [
                            '2 Konsep',
                            '25 File Edit',
                            '25 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ]
                ]
            ],
            'Maternity' => [
                'description' => 'Momen spesial kehamilan yang tak terlupakan',
                'packages' => [
                    [
                        'name' => 'Basic', 
                        'price' => 1000000, 
                        'duration' => 90, 
                        'description' => "1 Konsep\n15 File Edit\n15 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 15,
                        'features' => [
                            '1 Konsep',
                            '15 File Edit',
                            '15 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ],
                    [
                        'name' => 'Premium', 
                        'price' => 2000000, 
                        'duration' => 120, 
                        'description' => "2 Konsep\n25 File Edit\n25 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 25,
                        'features' => [
                            '2 Konsep',
                            '25 File Edit',
                            '25 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ]
                ]
            ],
            'Prewedding' => [
                'description' => 'Kenangan indah sebelum hari pernikahan Anda',
                'packages' => [
                    [
                        'name' => 'Paket Prewedding 1', 
                        'price' => 1500000, 
                        'duration' => 120, 
                        'description' => "1 Konsep\n10 File Edit\n10 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar\n1x Baju",
                        'max_photos' => 10,
                        'features' => [
                            '1 Konsep',
                            '10 File Edit',
                            '10 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar',
                            '1x Baju'
                        ]
                    ],
                    [
                        'name' => 'Paket Prewedding 2', 
                        'price' => 2500000, 
                        'duration' => 180, 
                        'description' => "2 Konsep\n20 File Edit\n20 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar\n2x Baju",
                        'max_photos' => 20,
                        'features' => [
                            '2 Konsep',
                            '20 File Edit',
                            '20 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar',
                            '2x Baju'
                        ]
                    ],
                    [
                        'name' => 'Paket Prewedding 3', 
                        'price' => 3500000, 
                        'duration' => 240, 
                        'description' => "3 Konsep\n30 File Edit\n30 Cetak 4R\n3 Backdrop\n1x Pengambilan Gambar\n3x Baju",
                        'max_photos' => 30,
                        'features' => [
                            '3 Konsep',
                            '30 File Edit',
                            '30 Cetak 4R',
                            '3 Backdrop',
                            '1x Pengambilan Gambar',
                            '3x Baju',
                            'Bonus 1 Cetak Ukuran 16R'
                        ]
                    ]
                ]
            ],
            'Profile' => [
                'description' => 'Foto profesional untuk keperluan profil pribadi atau bisnis',
                'packages' => [
                    [
                        'name' => 'Basic', 
                        'price' => 250000, 
                        'duration' => 60, 
                        'description' => "1 Konsep\n5 File Edit\n5 Cetak 4R\n1 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 5,
                        'features' => [
                            '1 Konsep',
                            '5 File Edit',
                            '5 Cetak 4R',
                            '1 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ],
                    [
                        'name' => 'Premium', 
                        'price' => 500000, 
                        'duration' => 120, 
                        'description' => "2 Konsep\n10 File Edit\n10 Cetak 4R\n2 Backdrop\n1x Pengambilan Gambar",
                        'max_photos' => 10,
                        'features' => [
                            '2 Konsep',
                            '10 File Edit',
                            '10 Cetak 4R',
                            '2 Backdrop',
                            '1x Pengambilan Gambar'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryName => $data) {
            // Create or update category
            $category = Category::updateOrCreate(
                ['name' => $categoryName],
                [
                    'description' => $data['description'],
                    'slug' => Str::slug($categoryName),
                    'is_active' => true
                ]
            );

            // Add packages to category
            foreach ($data['packages'] as $packageData) {
                $package = Package::updateOrCreate(
                    ['name' => $packageData['name']],
                    [
                        'price' => $packageData['price'],
                        'duration_hours' => ceil($packageData['duration'] / 60), // Convert minutes to hours
                        'description' => $packageData['description'],
                        'photo_count' => $packageData['max_photos'],
                        'edited_photo_count' => $packageData['max_photos'],
                        'is_active' => true,
                        'features' => isset($packageData['features']) ? json_encode($packageData['features']) : null,
                        'slug' => \Illuminate\Support\Str::slug($packageData['name'])
                    ]
                );

                // Attach package to category if not already attached
                if (!$category->packages()->where('package_id', $package->id)->exists()) {
                    $category->packages()->attach($package->id);
                }
            }
        }
    }
}
