<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnaliseCurriculoArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analise_curriculo_arquivos', function (Blueprint $table) {
            $table->id('codigoAnaliseCurriculoArquivo');
            $table->unsignedBigInteger('codigoAnaliseCurriculo');
            $table->unsignedBigInteger('codigoArquivo');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoAnaliseCurriculo')->references('codigoAnaliseCurriculo')->on('analise_curriculo');
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
        Schema::dropIfExists('analise_curriculo_arquivos');
    }
}
