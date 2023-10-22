<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiencias', function (Blueprint $table) {
            $table->id('codigoExperiencia');
            $table->unsignedBigInteger('codigoUsuario');
            $table->unsignedBigInteger('codigoTipoExperiencia');
            $table->unsignedBigInteger('codigoTipoEntidade')->default(1);
            $table->string('entidadeExperiencia');
            $table->string('posicaoExperiencia');
            $table->date('inicioExperiencia');
            $table->date('finalExperiencia');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoUsuario')->references('id')->on('users');
            $table->foreign('codigoTipoExperiencia')->references('codigoTipoExperiencia')->on('tipo_experiencia');
            $table->foreign('codigoTipoEntidade')->references('codigoTipoEntidade')->on('tipo_entidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiencias');
    }
}
