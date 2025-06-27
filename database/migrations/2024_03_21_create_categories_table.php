<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default categories
        DB::table('categories')->insert([
            ['name' => 'Meja', 'slug' => 'meja', 'description' => 'Berbagai jenis meja', 'icon' => 'fa-table'],
            ['name' => 'Kursi', 'slug' => 'kursi', 'description' => 'Berbagai jenis kursi', 'icon' => 'fa-chair'],
            ['name' => 'Dipan', 'slug' => 'dipan', 'description' => 'Berbagai jenis dipan', 'icon' => 'fa-bed'],
            ['name' => 'Rak', 'slug' => 'rak', 'description' => 'Berbagai jenis rak', 'icon' => 'fa-shelves'],
            ['name' => 'Lemari', 'slug' => 'lemari', 'description' => 'Berbagai jenis lemari', 'icon' => 'fa-wardrobe'],
            ['name' => 'Sofa', 'slug' => 'sofa', 'description' => 'Berbagai jenis sofa', 'icon' => 'fa-couch'],
            ['name' => 'Tempat Tidur', 'slug' => 'tempat-tidur', 'description' => 'Berbagai jenis tempat tidur', 'icon' => 'fa-bed'],
            ['name' => 'Kabinet', 'slug' => 'kabinet', 'description' => 'Berbagai jenis kabinet', 'icon' => 'fa-cabinet'],
            ['name' => 'Meja Rias', 'slug' => 'meja-rias', 'description' => 'Berbagai jenis meja rias', 'icon' => 'fa-mirror'],
            ['name' => 'Meja Makan', 'slug' => 'meja-makan', 'description' => 'Berbagai jenis meja makan', 'icon' => 'fa-utensils']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}; 