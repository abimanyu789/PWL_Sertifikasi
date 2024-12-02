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
        Schema::table('upload_sertifikat', function (Blueprint $table) {
            $table->enum('jenis_kegiatan', ['Sertifikasi', 'Pelatihan'])->default('Sertifikasi')->after('user_id');;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upload_sertifikat', function (Blueprint $table) {
            $table->dropColumn('jenis_kegiatan');
        });
    }
};
