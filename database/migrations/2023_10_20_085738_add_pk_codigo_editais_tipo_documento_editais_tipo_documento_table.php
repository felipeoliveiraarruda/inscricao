<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPkCodigoEditaisTipoDocumentoEditaisTipoDocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editais_tipo_documentos', function (Blueprint $table) {
            $table->id('codigoEditalTipoDocumento')->before('codigoEdital');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('editais_tipo_documentos', function (Blueprint $table) {
            $table->dropPrimary();
            $table->unsignedInteger('codigoEditalTipoDocumento'); // for removing auto increment
        }); 
    }
}
