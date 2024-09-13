<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesPessoais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_pessoais', function (Blueprint $table) {
            $table->id('codigoInscricaoPessoal');
            $table->unsignedBigInteger('codigoInscricao');
            $table->unsignedBigInteger('codigoPessoal');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoInscricao')->references('codigoInscricao')->on('inscricoes');
            $table->foreign('codigoPessoal')->references('codigoPessoal')->on('pessoais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricoes_pessoais');
    }
}
