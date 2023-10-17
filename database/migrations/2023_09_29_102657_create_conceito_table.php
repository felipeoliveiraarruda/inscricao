<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceitoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceito', function (Blueprint $table) {
            $table->id('codigoConceito');
            $table->string('descricaoConceito'); 
            $table->double('valorConceito', 4, 2); 
            $table->string('calculoConceito');
            $table->char('statusConceito', 1)->default('S');
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
        Schema::dropIfExists('conceito');
    }
}
