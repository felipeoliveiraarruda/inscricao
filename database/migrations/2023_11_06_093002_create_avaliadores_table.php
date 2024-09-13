<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliadores', function (Blueprint $table) {
            $table->id('codigoAvaliador');
            $table->unsignedBigInteger('codigoEdital');
            $table->unsignedBigInteger('codigoUsuario');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoEdital')->references('codigoEdital')->on('editais');
            $table->foreign('codigoUsuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliadores');
    }
}
