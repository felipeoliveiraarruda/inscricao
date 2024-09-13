<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoAnaliseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_analise', function (Blueprint $table) {
            $table->id('codigoTipoAnalise');
            $table->string('tipoAnalise'); 
            $table->string('calculoTipoAnalise'); 
            $table->string('pontuacaoTipoAnalise');
            $table->integer('maximoTipoAnalise');
            $table->char('statusTipoAnalise', 1)->default('S');
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
        Schema::dropIfExists('tipo_analise');
    }
}
