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
        Schema::create('m_level_sertifikasi', function (Blueprint $table) {
            $table->id('level_sertif_id');
            $table->string('level_sertif_kode',10)->unique();
            $table->string('level_sertif_nama',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_level_sertifikasi');
    }
};
