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
        Schema::table('upload_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['pelatihan_id']);
            $table->dropColumn('pelatihan_id');

            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upload_pelatihan', function (Blueprint $table) {
            $table->unsignedBigInteger('pelatihan_id')->index()->nullable();
            $table->unsignedBigInteger('vendor_id')->index()->nullable();

            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('m_pelatihan');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
        });
    }
};
