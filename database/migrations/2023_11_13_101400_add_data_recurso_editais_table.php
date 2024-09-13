<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataRecursoEditaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editais', function (Blueprint $table) {
            $table->timestamp('dataInicioRecurso')->nullable()->after('dataDoeEdital');
            $table->timestamp('dataFinalRecurso')->nullable()->after('dataInicioRecurso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('editais', function (Blueprint $table) {
            $table->dropColumn('dataInicioRecurso');
            $table->dropColumn('dataFinalRecurso');
        }); 
    }
}
