<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wisata_nearby_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisata')->cascadeOnDelete();
            $table->string('nama', 255);
            $table->string('kategori', 50)->default('other');
            $table->string('alamat', 255)->nullable();
            $table->string('latitude', 255)->nullable();
            $table->string('longitude', 255)->nullable();
            $table->timestamps();

            $table->index(['wisata_id', 'kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata_nearby_places');
    }
};
