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
        Schema::create('m_pelatihan', function (Blueprint $table) {
            $table->id('pelatihan_id');
            $table->string('nama_pelatihan');
            $table->date('tanggal');
            $table->unsignedBigInteger('kuota');
            $table->string('lokasi');
            $table->unsignedBigInteger('biaya');
            $table->string('level_pelatihan');
            $table->unsignedBigInteger('vendor_id')->index;
            $table->unsignedBigInteger('jenis_id')->index;
            $table->unsignedBigInteger('mk_id')->index;
            $table->unsignedBigInteger('periode_id')->index;
            $table->timestamps();

            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
            $table->foreign('jenis_id')->references('jenis_id')->on('m_jenis');
            $table->foreign('mk_id')->references('mk_id')->on('m_mata_kuliah');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_pelatihan');
    }
};
