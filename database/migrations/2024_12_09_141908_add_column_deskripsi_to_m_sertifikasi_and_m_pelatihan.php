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
        Schema::table('m_sertifikasi', function (Blueprint $table) {
            $table->string('deskripsi')->nullable()->after('nama_sertifikasi');
        });
        Schema::table('m_pelatihan', function (Blueprint $table) {
            $table->string('deskripsi')->nullable()->after('nama_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_sertifikasi', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
        Schema::table('m_pelatihan', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
};
