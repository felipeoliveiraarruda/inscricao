<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pae', function (Blueprint $table) {
            $table->id('codigoPae');
            $table->unsignedBigInteger('codigoInscricao');
            $table->char('partipacaoPae', 1)->default('N');
            $table->char('remuneracaoPae', 1)->default('N');
            $table->char('resultadoPae', 1)->default('N')->nullable();
            $table->integer('classificacaoPae')->nullable();
            $table->string('motivoPae')->nullable();
            $table->string('observacoesPae')->nullable();
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
        Schema::dropIfExists('pae');
    }
}
