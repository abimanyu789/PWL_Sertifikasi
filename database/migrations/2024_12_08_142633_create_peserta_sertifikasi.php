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
        Schema::create('peserta_sertifikasi', function (Blueprint $table) {
            $table->id('peserta_sertifikasi_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->unsignedBigInteger('sertifikasi_id')->index;
            $table->string('status');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('sertifikasi_id')->references('sertifikasi_id')->on('m_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_sertifikasi');
    }
};