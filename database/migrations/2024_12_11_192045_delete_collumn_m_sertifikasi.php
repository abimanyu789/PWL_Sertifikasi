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
        Schema::table('upload_sertifikasi', function (Blueprint $table) {
            $table->dropForeign(['sertifikasi_id']);
            $table->dropColumn('sertifikasi_id');

            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upload_sertifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('sertifikasi_id')->index()->nullable();
            $table->unsignedBigInteger('vendor_id')->index()->nullable();

            $table->foreign('sertifikasi_id')->references('sertifikasi_id')->on('m_sertifikasi');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
        });
    }
};
