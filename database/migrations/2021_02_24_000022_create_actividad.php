<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 128);
            $table->string('foto', 256)->nullable();
            $table->integer('edad');
            $table->text('descripcion');
            $table->dateTime('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actividad', function (Blueprint $table) {
            Schema::dropIfExists('actividad');
        });
    }
}
