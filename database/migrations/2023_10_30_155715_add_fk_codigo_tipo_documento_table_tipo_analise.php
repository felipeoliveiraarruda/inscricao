<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkCodigoTipoDocumentoTableTipoAnalise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_analise', function (Blueprint $table) {
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
        Schema::table('tipo_analise', function (Blueprint $table) {
            $table->dropForeign('codigoTipoDocumento');
        }); 
    }
}
