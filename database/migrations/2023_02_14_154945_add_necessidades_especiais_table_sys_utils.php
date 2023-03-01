<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNecessidadesEspeciaisTableSysUtils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_utils', function (Blueprint $table) {
            $table->string('dadosNecessidadeEspecial')->nullable()->after('dadosEstadoCivil');
        });
    }

    public function down()
    {
        Schema::table('sys_utils', function (Blueprint $table) {
            $table->dropColumn('dadosNecessidadeEspecial');
        });    
    }
}
