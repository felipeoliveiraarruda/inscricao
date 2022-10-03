<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id('codigoInscricao');
            $table->unsignedBigInteger('codigoEdital');
            $table->unsignedBigInteger('codigoUsuario');
            $table->char('situacaoInscricao', 1)->default('N');            
            $table->timestamps();
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
        Schema::dropIfExists('inscricoes');
    }
}
