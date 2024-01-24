<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDisciplinaTableInscricoesDisciplinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes_disciplinas', function (Blueprint $table) {
            $table->char('statusDisciplina')->default('N')->after('codigoDisciplina');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes_disciplinas', function (Blueprint $table) {
            $table->dropColumn('statusDisciplina');
        }); 
    }
}
