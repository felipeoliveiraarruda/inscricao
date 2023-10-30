<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoTipoDocumentoTableTipoAnalise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_analise', function (Blueprint $table) {
            $table->unsignedBigInteger('codigoTipoDocumento')->nullable()
                  ->after('codigoTipoAnalise');
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
            $table->dropColumn('codigoTipoDocumento');
        }); 
    }
}
