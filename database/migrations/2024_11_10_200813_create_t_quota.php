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
        Schema::create('t_quota', function (Blueprint $table) {
            $table->id('quota_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->unsignedBigInteger('pelatihan_id')->index;
            $table->unsignedBigInteger('vendor_id')->index;
            $table->integer('quota_jumlah');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('m_pelatihan');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_quota');
    }
};
