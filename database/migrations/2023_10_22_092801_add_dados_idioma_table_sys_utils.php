<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDadosIdiomaTableSysUtils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_utils', function (Blueprint $table) {
            $table->integer('dadosIdioma')
                  ->after('dadosNecessidadeEspecial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_utils', function (Blueprint $table) {
            $table->dropColumn('dadosIdioma');
        }); 
    }
}
