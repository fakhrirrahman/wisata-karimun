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
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('deskripsi');
            $table->string('kategori', 255);
            $table->string('kecamatan', 255);
            $table->string('alamat', 255);
            $table->string('latitude', 255);
            $table->string('longitude', 255);
            $table->integer('harga')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('visits')->default(0);
            $table->timestamp('last_visited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
