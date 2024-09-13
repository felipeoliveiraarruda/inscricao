<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesempenhoAcademicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desempenho_academico', function (Blueprint $table) {
            $table->id('codigoDesempenhoAcademico');
            $table->unsignedBigInteger('codigoPae');
            $table->unsignedBigInteger('codigoConceito');
            $table->integer('quantidadeDesempenhoAcademico');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoPae')->references('codigoPae')->on('pae');
            $table->foreign('codigoConceito')->references('codigoConceito')->on('conceito');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desempenho_academico');
    }
}
