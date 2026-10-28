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
        Schema::ensureVectorExtensionExists();

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100);
        });

        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100);
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 50);
            $table->text('description')->nullable();
            $table->decimal('price', total: 10, places: 2);
            $table->string('picture_file_name', length: 255)->nullable();
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->integer('available_stock');
            $table->integer('restock_threshold');
            $table->integer('max_stock_threshold');
            $table->vector('embedding', dimensions: 384)->nullable();
            $table->boolean('on_reorder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('types');
    }
};
