<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivos', function (Blueprint $table) {
            $table->id('codigoArquivo');
            $table->unsignedBigInteger('codigoUsuario');
            $table->unsignedBigInteger('codigoTipoDocumento');
            $table->string('linkArquivo');
            $table->timestamps();
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
        Schema::dropIfExists('arquivos');
    }
}
