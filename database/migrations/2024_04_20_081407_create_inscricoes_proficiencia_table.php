<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesProficienciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_proficiencia', function (Blueprint $table) {
            $table->id('codigoInscricaoProficiencia');
            $table->unsignedBigInteger('codigoInscricao');
            $table->integer('codigoPessoaOrientador');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoInscricao')->references('codigoInscricao')->on('inscricoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricoes_proficiencia');
    }
}
