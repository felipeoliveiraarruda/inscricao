<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumoEscolarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumo_escolar', function (Blueprint $table) {
            $table->id('codigoResumoEscolar');
            $table->unsignedBigInteger('codigoUsuario');
            $table->string('escolaResumoEscolar');
            $table->string('especialidadeResumoEscolar');
            $table->date('inicioResumoEscolar');
            $table->date('finalResumoEscolar');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
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
        Schema::dropIfExists('resumo_escolar');
    }
}
