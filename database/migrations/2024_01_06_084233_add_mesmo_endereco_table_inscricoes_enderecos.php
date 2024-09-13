<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMesmoEnderecoTableInscricoesEnderecos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes_enderecos', function (Blueprint $table) {
            $table->char('mesmoEndereco', 1)->default('N')
                  ->after('codigoEmergencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes_enderecos', function (Blueprint $table) {
            $table->dropColumn('mesmoEndereco');
        }); 
    }
}
