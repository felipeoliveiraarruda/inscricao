<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSolicitarBolsaTableRecursosFinanceiros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recursos_financeiros', function (Blueprint $table) {
            $table->char('anoTitulacaoRecursoFinanceiro', 4)->nullable()->after('finalRecursoFinanceiro');
            $table->string('iesTitulacaoRecursoFinanceiro')->nullable()->after('anoTitulacaoRecursoFinanceiro');
            $table->string('agenciaRecursoFinanceiro', 10)->nullable()->after('iesTitulacaoRecursoFinanceiro');
            $table->string('contaRecursoFinanceiro', 20)->nullable()->after('agenciaRecursoFinanceiro');
            $table->string('localRecursoFinanceiro', 100)->nullable()->after('contaRecursoFinanceiro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recursos_financeiros', function (Blueprint $table) {
            $table->dropColumn('anoTitulacaoRecursoFinanceiro');
            $table->dropColumn('iesTitulacaoRecursoFinanceiro');
            $table->dropColumn('agenciaRecursoFinanceiro');
            $table->dropColumn('contaRecursoFinanceiro');
            $table->dropColumn('localRecursoFinanceiro');
        }); 
    }
}
