<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdemEditalTableEditaisTipoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editais_tipo_documentos', function (Blueprint $table) {
            $table->string('ordemTipoDocumento', 10)->nullable()->after('codigoTipoDocumento');
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
            $table->dropColumn('ordemTipoDocumento');
        }); 
    }
}
