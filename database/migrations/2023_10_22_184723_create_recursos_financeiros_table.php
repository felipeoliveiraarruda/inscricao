<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecursosFinanceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursos_financeiros', function (Blueprint $table) {
            $table->id('codigoRecursoFinanceiro');
            $table->unsignedBigInteger('codigoUsuario');
            $table->char('bolsaRecursoFinanceiro', 1)->default('N');
            $table->char('solicitarRecursoFinanceiro', 1)->default('N');
            $table->string('orgaoRecursoFinanceiro')->nullable();
            $table->date('inicioRecursoFinanceiro')->nullable();
            $table->date('finalRecursoFinanceiro')->nullable();
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
        Schema::dropIfExists('recursos_financeiros');
    }
}
