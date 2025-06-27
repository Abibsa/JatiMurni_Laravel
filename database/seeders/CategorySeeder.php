<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Meja',
                'description' => 'Berbagai jenis meja kayu jati'
            ],
            [
                'name' => 'Kursi',
                'description' => 'Berbagai jenis kursi kayu jati'
            ],
            [
                'name' => 'Lemari',
                'description' => 'Berbagai jenis lemari kayu jati'
            ],
            [
                'name' => 'Tempat Tidur',
                'description' => 'Berbagai jenis tempat tidur kayu jati'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name'] . '-' . Str::random(5)),
                'description' => $category['description']
            ]);
        }
    }
} 