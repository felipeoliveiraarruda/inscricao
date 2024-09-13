<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovoAnaliseCurriculoTable extends Migration
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
            $table->unsignedBigInteger('codigoArquivo');
            $table->integer('pontuacaoAnaliseCurriculo');
            $table->char('statusAnaliseCurriculo', 1)->default('N');
            $table->text('justificativaAnaliseCurriculo');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoPae')->references('codigoPae')->on('pae');
            $table->foreign('codigoArquivo')->references('codigoArquivo')->on('arquivos');
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
