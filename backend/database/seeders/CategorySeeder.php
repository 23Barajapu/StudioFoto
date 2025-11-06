<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Self Photo Studio', 'slug' => 'self-photo-studio'],
            ['name' => 'Keluarga', 'slug' => 'keluarga'],
            ['name' => 'Maternity', 'slug' => 'maternity'],
            ['name' => 'Prewedding', 'slug' => 'prewedding'],
            ['name' => 'Grup', 'slug' => 'grup'],
            ['name' => 'Pas Foto', 'slug' => 'pas-foto'],
            ['name' => 'Profile', 'slug' => 'profile'],
        ];  

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
