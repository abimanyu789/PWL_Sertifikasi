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
        Schema::create('m_quota', function (Blueprint $table) {
            $table->id('quota_id');
            $table->unsignedBigInteger('pelatihan_id')->index;
            $table->integer('quota_jumlah');
            $table->timestamps();

            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('m_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_quota');
    }
};