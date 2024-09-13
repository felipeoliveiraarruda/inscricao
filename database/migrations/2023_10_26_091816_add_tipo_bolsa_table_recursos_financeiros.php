<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoBolsaTableRecursosFinanceiros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recursos_financeiros', function (Blueprint $table) {
            $table->string('tipoBolsaFinanceiro')->nullable()
                  ->after('orgaoRecursoFinanceiro');
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
            $table->dropColumn('tipoBolsaFinanceiro');
        }); 
    }
}
