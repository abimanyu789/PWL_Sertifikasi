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
        Schema::create('upload_pelatihan', function (Blueprint $table) {
            $table->id('upload_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->unsignedBigInteger('pelatihan_id')->index;
            $table->string('nama_sertif');
            $table->string('no_sertif')->unique();
            $table->date('tanggal');
            $table->date('masa_berlaku');
            $table->unsignedBigInteger('jenis_id')->index;
            $table->unsignedBigInteger('vendor_id')->index;
            $table->string('nama_vendor');
            $table->string('bukti');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('m_pelatihan');
            $table->foreign('jenis_id')->references('jenis_id')->on('m_jenis');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_pelatihan');
    }
};
