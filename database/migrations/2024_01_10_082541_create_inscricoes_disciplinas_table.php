<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_disciplinas', function (Blueprint $table) {
            $table->id('codigoInscricaoDisciplina');
            $table->unsignedBigInteger('codigoInscricao');
            $table->string('codigoDisciplina', 10);            
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
        Schema::dropIfExists('inscricoes_disciplinas');
    }
}
