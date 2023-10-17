<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id('codigoEndereco');
            $table->unsignedBigInteger('codigoUsuario');
            $table->char('cepEndereco', 9)->nullable();
            $table->string('logradouroEndereco')->nullable();
            $table->string('numeroEndereco', 10)->nullable();
            $table->string('complementoEndereco')->nullable();            
            $table->string('bairroEndereco')->nullable();
            $table->string('localidadeEndereco')->nullable();
            $table->char('ufEndereco', 2)->nullable();            
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('enderecos');
    }
}

