<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecursoPaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurso_pae', function (Blueprint $table) {
            $table->id('codigoRecurso');
            $table->unsignedBigInteger('codigoPae');
            $table->text('justificativaRecurso');
            $table->text('analiseRecurso');
            $table->char('statusRecurso', 1)->default('A');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('codigoPessoaAlteracao');
            $table->foreign('codigoPae')->references('codigoPae')->on('pae');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurso_pae');
    }
}
