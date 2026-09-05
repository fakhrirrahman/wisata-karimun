<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wisata_visits', function (Blueprint $table) {
            $table->dropForeign(['wisata_id']);
            $table->foreignId('wisata_id')->nullable()->change();
            $table->foreign('wisata_id')->references('id')->on('wisata')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('wisata_visits')->whereNull('wisata_id')->delete();

        Schema::table('wisata_visits', function (Blueprint $table) {
            $table->dropForeign(['wisata_id']);
            $table->foreignId('wisata_id')->nullable(false)->change();
            $table->foreign('wisata_id')->references('id')->on('wisata')->cascadeOnDelete();
        });
    }
};
