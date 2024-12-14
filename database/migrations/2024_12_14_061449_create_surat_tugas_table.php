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
        Schema::create('surat_tugas', function (Blueprint $table) {
    
                $table->id('surat_tugas_id');
                $table->unsignedBigInteger('peserta_sertifikasi_id')->index;
                $table->unsignedBigInteger('peserta_pelatihan_id')->index;
                $table->unsignedBigInteger('user_id')->index;
                $table->timestamps();
    
                $table->foreign('peserta_sertifikasi_id')->references('peserta_sertifikasi_id')->on('peserta_sertifikasi');
                $table->foreign('peserta_pelatihan_id')->references('peserta_pelatihan_id')->on('peserta_pelatihan');
                $table->foreign('user_id')->references('user_id')->on('m_user');
            
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
            Schema::dropIfExists('surat_tugas');

        
    }
};
