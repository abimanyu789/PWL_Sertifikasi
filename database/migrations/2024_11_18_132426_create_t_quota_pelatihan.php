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
        Schema::create('t_quota_pelatihan', function (Blueprint $table) {
            $table->id('quota_pelatihan_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->unsignedBigInteger('quota_id')->index;
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('quota_id')->references('quota_id')->on('m_quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_quota_pelatihan');
    }
};
