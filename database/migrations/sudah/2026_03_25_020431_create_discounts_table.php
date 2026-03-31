<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('odoo_product_id');
            $table->enum('type',['value','percentage']);
            $table->decimal('amount',15,2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('image_flyer')->nullable();
            $table->text('description')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
