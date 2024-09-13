<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiveisTable extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('niveis', function (Blueprint $table) {
            $table->id('codigoNivel');
            $table->string('descricaoNivel');
            $table->char('siglaNivel', 2);            
            $table->char('ativoNivel', 1)->default('S');            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('niveis');
    }
}
