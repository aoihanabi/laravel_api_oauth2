<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfirmacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iduser');
            $table->integer('idactividad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('confirmacion', function (Blueprint $table) {
            Schema::dropIfExists("confirmacion");
        });
    }
}
