<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclaracaoAcumuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaracao_acumulo', function (Blueprint $table) {
            $table->id('codigoDeclaracaoAcumulo');            
            $table->unsignedBigInteger('codigoInscricao');
            $table->text('atividadeRemunerada')->nullable();
            $table->text('outroRendimento')->nullable();
            $table->text('bolsaDeclaratoria')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoInscricao')->references('codigoInscricao')->on('inscricoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declaracao_acumulo');
    }
}
