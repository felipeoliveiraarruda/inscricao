<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorTipoAnaliseTableTipoAnalise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_analise', function (Blueprint $table) {
            $table->double('valorTipoAnalise', 8, 2)
                  ->after('tipoAnalise');
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
            $table->dropColumn('valorTipoAnalise');
        }); 
    }
}
