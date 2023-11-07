<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliadoresPaeNovoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliadores_pae', function (Blueprint $table) {
            $table->id('codigoAvaliadorPae');
            $table->unsignedBigInteger('codigoAvaliador');
            $table->unsignedBigInteger('codigoPae');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoAvaliador')->references('codigoAvaliador')->on('avaliadores');
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
