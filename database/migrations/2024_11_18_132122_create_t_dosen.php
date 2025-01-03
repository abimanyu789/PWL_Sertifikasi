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
        Schema::create('t_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->unsignedBigInteger('bidang_id')->index;
            $table->unsignedBigInteger('mk_id')->index;
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
            $table->foreign('mk_id')->references('mk_id')->on('m_mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_dosen');
    }
};
