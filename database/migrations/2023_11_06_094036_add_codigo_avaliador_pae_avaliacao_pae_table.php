<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoAvaliadorPaeAvaliacaoPaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliacao_pae', function (Blueprint $table) {
            $table->unsignedBigInteger('codigoAvaliadorPae')->after('codigoAvaliacao');
            $table->foreign('codigoAvaliadorPae')->references('codigoAvaliadorPae')->on('avaliadores_pae');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliacao_pae', function (Blueprint $table) {
            $table->dropColumn('codigoAvaliadorPae');
        }); 
    }
}
