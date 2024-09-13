<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesResumoEscolarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_resumo_escolar', function (Blueprint $table) {
            $table->id('codigoInscricaoResumoEscolar');
            $table->unsignedBigInteger('codigoInscricao');
            $table->unsignedBigInteger('codigoResumoEscolar');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoInscricao')->references('codigoInscricao')->on('inscricoes');
            $table->foreign('codigoResumoEscolar')->references('codigoResumoEscolar')->on('resumo_escolar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricoes_resumo_escolar');
    }
}
