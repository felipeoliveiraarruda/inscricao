<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUtils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_utils', function (Blueprint $table) {
            $table->id('codigoSysUtils');
            $table->string('dadosSexo')->nullable();
            $table->string('dadosRaca')->nullable();
            $table->string('dadosEstadoCivil')->nullable();
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
        Schema::dropIfExists('sys_utils');
    }
}
