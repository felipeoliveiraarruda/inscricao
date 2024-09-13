<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesRecursosFinanceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_recursos_financeiros', function (Blueprint $table) {
            $table->id('codigoInscricaoRecursoFinanceiro');
            $table->unsignedBigInteger('codigoInscricao');
            $table->unsignedBigInteger('codigoRecursoFinanceiro');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoInscricao')->references('codigoInscricao')->on('inscricoes');
            $table->foreign('codigoRecursoFinanceiro')->references('codigoRecursoFinanceiro')->on('recursos_financeiros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricoes_recursos_financeiros');
    }
}
