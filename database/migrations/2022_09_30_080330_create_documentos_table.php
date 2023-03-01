<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id('codigoDocumento');
            $table->unsignedBigInteger('codigoUsuario');
            $table->string('numeroRG', 20)->nullable();
            $table->char('ufEmissorRG', 2)->nullable();
            $table->string('orgaoEmissorRG', 10)->nullable();
            $table->date('dataEmissaoRG')->nullable();
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
        Schema::dropIfExists('documentos');
    }
}
