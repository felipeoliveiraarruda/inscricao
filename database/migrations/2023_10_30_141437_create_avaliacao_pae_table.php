<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacaoPaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacao_pae', function (Blueprint $table) {
            $table->id('codigoAvaliacao');
            $table->unsignedBigInteger('codigoPae');
            $table->unsignedBigInteger('codigoTipoAnalise');
            $table->integer('pontuacaoAvaliacao');
            $table->double('totalAvaliacao', 8, 2);
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
        Schema::dropIfExists('avaliacao_pae');
    }
}
