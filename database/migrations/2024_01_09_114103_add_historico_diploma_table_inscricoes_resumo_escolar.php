<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoricoDiplomaTableInscricoesResumoEscolar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes_resumo_escolar', function (Blueprint $table) {
            $table->integer('codigoHistorico')->after('codigoResumoEscolar');
            $table->integer('codigoDiploma')->after('codigoHistorico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes_resumo_escolar', function (Blueprint $table) {
            $table->dropColumn('codigoHistorico');
            $table->dropColumn('codigoDiploma');
        }); 
    }
}
