<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditaisEstagiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editais_estagios', function (Blueprint $table) {
            $table->id('codigoEditalEstagio');            
            $table->string('descricaoEditalEstagio');
            $table->string('linkEditalEstagio');
            $table->timestamp('dataInicioEditalEstagio');
            $table->timestamp('dataFinalEditalEstagio');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('editais_estagios');
    }
}
