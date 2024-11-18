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
        // Menggunakan Schema::create untuk membuat tabel baru
        Schema::create('m_matkul', function (Blueprint $table) {
            $table->id('matkul_id');
            $table->string('matkul_nama', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel jika rollback
        Schema::dropIfExists('m_matkul');
    }
};
