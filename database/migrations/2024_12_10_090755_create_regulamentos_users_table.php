<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulamentosUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulamentos_users', function (Blueprint $table) {
            $table->id('codigoRegulamentoUser');
            $table->unsignedBigInteger('codigoRegulamento');
            $table->unsignedBigInteger('codigoUsuario');
            $table->char('statusRegulamento', 1)->default('N');
            $table->string('linkArquivo')->nullable();                
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoRegulamento')->references('codigoRegulamento')->on('regulamentos');
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
        Schema::dropIfExists('regulamentos_users');
    }
}
