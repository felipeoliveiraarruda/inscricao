<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtivoTipoDocumentosTableTipoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            $table->char('ativoTipoDocumento', 1)->default('S')
                  ->after('tipoDocumento'); // Ordenado após a coluna "password"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            $table->dropColumn('ativoTipoDocumento');
        });  
    }
}
