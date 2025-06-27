<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // BIGINT (PK) Primary Key
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // BIGINT (FK) Relasi ke orders
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // BIGINT (FK) Relasi ke products
            $table->integer('quantity'); // INTEGER Jumlah produk
            $table->decimal('price', 10, 2); // DECIMAL(10,2) Harga per item
            $table->decimal('subtotal', 10, 2); // DECIMAL(10,2) Total harga per item
            $table->timestamps(); // TIMESTAMP created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}; 