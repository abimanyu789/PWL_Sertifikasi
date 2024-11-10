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
        Schema::create('upload_sertifikat', function (Blueprint $table) {
            $table->id('sertif_id');
            $table->unsignedBigInteger('user_id')->index;
            $table->string('no_sertif',100)->unique();
            $table->string('nama_sertif',100);
            $table->dateTime('tanggal_pelaksanaan');
            $table->dateTime('tanggal_berlaku');
            $table->unsignedBigInteger('bidang_id')->index;
            $table->string('nama_vendor',100);
            $table->string('image');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_sertifikat');
    }
};
