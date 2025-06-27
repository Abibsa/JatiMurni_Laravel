<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 10)->nullable()->unique();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('payment_method', 50);
            $table->date('payment_date');
            $table->decimal('amount_paid', 10, 2);
            $table->string('proof_image', 255)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->string('customer_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 