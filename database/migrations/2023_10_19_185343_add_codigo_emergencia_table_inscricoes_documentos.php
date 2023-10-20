<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoEmergenciaTableInscricoesDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes_enderecos', function (Blueprint $table) {
            $table->unsignedBigInteger('codigoEmergencia')->nullable()
                  ->after('codigoEndereco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes_enderecos', function (Blueprint $table) {
            $table->dropColumn('codigoEmergencia');
        }); 
    }
}
