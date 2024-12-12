<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulamentos', function (Blueprint $table) {
            $table->id('codigoRegulamento');
            $table->integer('codigoCurso');
            $table->string('descricaoRegulamento');
            $table->string('linkRegulamento');
            $table->timestamp('dataInicioRegulamento');
            $table->timestamp('dataFinalRegulamento');   
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regulamentos');
    }
}
