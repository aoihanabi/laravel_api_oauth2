<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userdata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iduser');
            $table->string('nombre', 128);
            $table->string('foto', 256)->nullable();
            $table->integer('edad');
            $table->text('acercade');
            $table->string('genero', 64);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userdata', function (Blueprint $table) {
            Schema::dropIfExists("userdata");
        });
    }
}
