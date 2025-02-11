<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEgressosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egressos', function (Blueprint $table) {
            $table->id('codigoEgresso');
            $table->string('egressoNome');
            $table->string('egressoEmail')->unique();
            $table->char('egressoRegular', 1);
            $table->string('egressoNivel')->nullable();
            $table->text('egressoLocal')->nullable();
            $table->text('egressoAtividade')->nullable();
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
        Schema::dropIfExists('egressos');
    }
}

