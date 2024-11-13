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
            $table->string('nama_pelatihan', 100);
            $table->string('deskripsi');
            $table->dateTime('tanggal');
            $table->unsignedBigInteger('bidang_id')->index;
            $table->unsignedBigInteger('level_pelatihan_id')->index;
            $table->unsignedBigInteger('vendor_id')->index;
            $table->timestamps();

            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
            $table->foreign('level_pelatihan_id')->references('level_pelatihan_id')->on('m_level_pelatihan');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
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
