<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivosGcubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivos_gcub', function (Blueprint $table) {
            $table->id('codigoArquivo');
            $table->unsignedBigInteger('codigoGcub');
            $table->unsignedBigInteger('codigoTipoDocumento');
            $table->string('linkArquivo');            
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('codigoGcub')->references('codigoGcub')->on('gcub');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arquivos_gcub');
    }
}
