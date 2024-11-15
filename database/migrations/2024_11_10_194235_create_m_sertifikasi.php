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
        Schema::create('m_sertifikasi', function (Blueprint $table) {
            $table->id('sertifikasi_id');
            $table->string('nama_sertifikasi', 100);
            $table->dateTime('tanggal');
            $table->unsignedBigInteger('bidang_id')->index;
            $table->unsignedBigInteger('jenis_id')->index;
            $table->dateTime('tanggal_berlaku');
            $table->timestamps();

            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
            $table->foreign('jenis_id')->references('jenis_id')->on('m_jenis_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_sertifikasi');
    }
};
