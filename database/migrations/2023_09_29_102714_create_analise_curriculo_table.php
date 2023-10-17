<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnaliseCurriculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analise_curriculo', function (Blueprint $table) {
            $table->id('codigoAnaliseCurriculo');
            $table->unsignedBigInteger('codigoPae');
            $table->unsignedBigInteger('codigoTipoAnalise');
            $table->integer('pontuacaoAnaliseCurriculo');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoPae')->references('codigoPae')->on('pae');
            $table->foreign('codigoTipoAnalise')->references('codigoTipoAnalise')->on('tipo_analise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analise_curriculo');
    }
}
