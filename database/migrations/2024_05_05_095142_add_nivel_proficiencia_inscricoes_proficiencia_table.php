<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNivelProficienciaInscricoesProficienciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes_proficiencia', function (Blueprint $table) {
            $table->char('nivelProficiencia', 2)->nullable()->after('codigoInscricao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes_proficiencia', function (Blueprint $table) {
            $table->dropColumn('nivelProficiencia');
        }); 
    }
}
