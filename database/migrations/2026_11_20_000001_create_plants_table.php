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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();

            // Core inventory fields.
            $table->string('name');
            $table->string('category')->index();
            $table->decimal('unit_price', 10, 2);
            $table->text('description');

            // Stock status is stored as a string so the two allowed values
            // (in_stock / out_of_stock) are enforced by our FormRequest too.
            $table->string('stock_status')->default('in_stock')->index();

            // Every plant belongs to the administrator who created it.
            // cascadeOnDelete keeps the inventory clean if a user is removed.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
