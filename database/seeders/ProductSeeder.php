<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel produk dulu
        DB::table('products')->truncate();

        $meja = Category::where('name', 'Meja')->first();
        $kursi = Category::where('name', 'Kursi')->first();

        if ($meja) {
            Product::create([
                'name' => 'Meja Jati Minimalis',
                'slug' => Str::slug('Meja Jati Minimalis'),
                'description' => 'Meja jati minimalis berkualitas tinggi, cocok untuk ruang tamu modern.',
                'price' => 2500000,
                'stock' => 10,
                'material' => 'Kayu Jati',
                'dimensions' => '120x60x75 cm',
                'category_id' => $meja->id,
                'image' => null
            ]);
        }

        if ($kursi) {
            Product::create([
                'name' => 'Kursi Jati Elegan',
                'slug' => Str::slug('Kursi Jati Elegan'),
                'description' => 'Kursi jati elegan dengan desain ergonomis dan finishing halus.',
                'price' => 1500000,
                'stock' => 5,
                'material' => 'Kayu Jati',
                'dimensions' => '45x45x90 cm',
                'category_id' => $kursi->id,
                'image' => null
            ]);
        }
    }
} 