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
        Schema::create('m_mandiri', function (Blueprint $table) {
            $table->id('mandiri_id');
            $table->unsignedBigInteger('jenis_id')->index;  //fk
            $table->string('nama',100);
            $table->timestamps();

            // fk
            $table->foreign('jenis_id')->references('jenis_id')->on('m_jenis_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_mandiri');
    }
};
