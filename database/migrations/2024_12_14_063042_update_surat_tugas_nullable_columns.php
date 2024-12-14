<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSuratTugasNullableColumns extends Migration
{
    public function up()
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_sertifikasi_id')->nullable()->change();
            $table->unsignedBigInteger('peserta_pelatihan_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_sertifikasi_id')->nullable(false)->change();
            $table->unsignedBigInteger('peserta_pelatihan_id')->nullable(false)->change();
        });
    }
}