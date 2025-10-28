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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('duration_hours')->default(2); // Durasi sesi foto dalam jam
            $table->integer('photo_count')->default(50); // Jumlah foto yang diberikan
            $table->integer('edited_photo_count')->default(10); // Jumlah foto yang di-edit
            $table->boolean('include_makeup')->default(false);
            $table->boolean('include_outfit')->default(false);
            $table->text('features')->nullable(); // JSON array of features
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
