<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\Package;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();

        $galleries = [
            [
                'title' => 'Romantic Prewedding Session',
                'description' => 'Sesi foto prewedding romantis dengan konsep outdoor',
                'image_path' => 'galleries/sample1.jpg',
                'thumbnail_path' => 'galleries/sample1_thumb.jpg',
                'package_id' => $packages->where('slug', 'premium-package')->first()?->id,
                'category' => 'prewedding',
                'is_featured' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Wedding Day Moments',
                'description' => 'Dokumentasi pernikahan dengan momen-momen berharga',
                'image_path' => 'galleries/sample2.jpg',
                'thumbnail_path' => 'galleries/sample2_thumb.jpg',
                'package_id' => $packages->where('slug', 'wedding-package')->first()?->id,
                'category' => 'wedding',
                'is_featured' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Professional Portrait',
                'description' => 'Foto portrait profesional untuk keperluan bisnis',
                'image_path' => 'galleries/sample3.jpg',
                'thumbnail_path' => 'galleries/sample3_thumb.jpg',
                'package_id' => $packages->where('slug', 'basic-package')->first()?->id,
                'category' => 'portrait',
                'is_featured' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Family Portrait Session',
                'description' => 'Sesi foto keluarga yang hangat dan penuh cinta',
                'image_path' => 'galleries/sample4.jpg',
                'thumbnail_path' => 'galleries/sample4_thumb.jpg',
                'package_id' => $packages->where('slug', 'standard-package')->first()?->id,
                'category' => 'portrait',
                'is_featured' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Product Photography',
                'description' => 'Foto produk berkualitas tinggi untuk keperluan marketing',
                'image_path' => 'galleries/sample5.jpg',
                'thumbnail_path' => 'galleries/sample5_thumb.jpg',
                'package_id' => null,
                'category' => 'product',
                'is_featured' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }

        $this->command->info('Gallery items created successfully!');
    }
}
