<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditaisTipoDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editais_tipo_documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('codigoEdital');
            $table->unsignedBigInteger('codigoTipoDocumento');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoEdital')->references('codigoEdital')->on('editais');
            $table->foreign('codigoTipoDocumento')->references('codigoTipoDocumento')->on('tipo_documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('editais_tipo_documentos');
    }
}
