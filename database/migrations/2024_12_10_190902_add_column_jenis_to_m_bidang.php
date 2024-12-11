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
        Schema::table('m_bidang', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_id')->after('bidang_nama');
            $table->foreign('jenis_id')->references('jenis_id')->on('m_jenis');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_bidang', function (Blueprint $table) {
            $table->dropColumn('jenis_id');
        });
    }
};