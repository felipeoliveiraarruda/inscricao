<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataNascimentoTablePessoais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoais', function (Blueprint $table) {
            $table->date('dataNascimentoPessoal')->nullable()
                    ->after('codigoUsuario'); // Ordenado após a coluna "password"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoais', function (Blueprint $table) {
            $table->dropColumn('dataNascimentoPessoal');
        });        
    }
}
