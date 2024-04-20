<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoResumoTableResumoEscolar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resumo_escolar', function (Blueprint $table) {
            $table->string('tipoResumoEscolar')->nullable()->after('especialidadeResumoEscolar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resumo_escolar', function (Blueprint $table) {
            $table->dropColumn('tipoResumoEscolar');
        }); 
    }
}
