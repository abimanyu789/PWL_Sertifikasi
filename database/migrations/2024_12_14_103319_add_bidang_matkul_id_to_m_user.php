<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('m_user', function (Blueprint $table) {
            $table->text('bidang_id')->nullable()->after('level_id');
            $table->text('mk_id')->nullable()->after('bidang_id');
        });
    }

    public function down()
    {
        Schema::table('m_user', function (Blueprint $table) {
            $table->dropColumn(['bidang_id', 'mk_id']);
        });
    }
};