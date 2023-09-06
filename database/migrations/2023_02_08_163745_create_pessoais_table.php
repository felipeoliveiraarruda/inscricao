<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoais', function (Blueprint $table) {
            $table->id('codigoPessoal');
            $table->unsignedBigInteger('codigoUsuario')->unique();
            $table->string('sexoPessoal', 10)->nullable();
            $table->string('estadoCivilPessoal', 20)->nullable();
            $table->string('naturalidadePessoal', 50)->nullable();
            $table->char('dependentePessoal', 1)->nullable();
            $table->char('racaPessoal', 20)->nullable();
            $table->char('especialPessoal', 1)->nullable();
            $table->string('tipoEspecialPessoal', 20)->nullable();
            $table->timestamps();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoUsuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoais');
    }
}
