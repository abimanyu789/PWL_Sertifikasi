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
        Schema::create('m_vendor', function (Blueprint $table) {
            $table->id('vendor_id');
            $table->string('vendor_nama', 100)->unique();
            $table->string('alamat', 100);
            $table->string('kota', 100);
            $table->string('no_telp', 20);
            $table->string('alamat_web',200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_vendor');
    }
};
