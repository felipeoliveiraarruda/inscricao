<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliadoresPaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliadores_pae', function (Blueprint $table) {
            $table->id('codigoAvaliacao');
            $table->unsignedBigInteger('codigoPae');
            $table->unsignedBigInteger('codigoPessoa');
            $table->unsignedBigInteger('codigoCurso');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoPae')->references('codigoPae')->on('pae');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliadores_pae');
    }
}
