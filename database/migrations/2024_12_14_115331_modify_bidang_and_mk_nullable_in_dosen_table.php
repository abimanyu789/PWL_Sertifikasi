<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('t_dosen', function (Blueprint $table) {
            $table->bigInteger('bidang_id')->unsigned()->nullable()->change();
            $table->bigInteger('mk_id')->unsigned()->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('t_dosen', function (Blueprint $table) {
            $table->bigInteger('bidang_id')->unsigned()->nullable(false)->change();
            $table->bigInteger('mk_id')->unsigned()->nullable(false)->change();
        });
    }
};