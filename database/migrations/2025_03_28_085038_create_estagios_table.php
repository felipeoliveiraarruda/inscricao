<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstagiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estagios', function (Blueprint $table) {
            $table->id('codigoEstagio');
            $table->unsignedBigInteger('codigoEditalEstagio');
            $table->char('cpfEstagio', 11)->unique();
            $table->string('nomeEstagio');
            $table->string('emailEstagio');
            $table->char('telefoneEstagio', 20);
            $table->string('cursoEstagio');
            $table->string('semestreEstagio');
            $table->char('cepEnderecoEstagio', 9)->nullable();
            $table->string('logradouroEnderecoEstagio')->nullable();
            $table->string('numeroEnderecoEstagio', 10)->nullable();
            $table->string('complementoEnderecoEstagio')->nullable();            
            $table->string('bairroEnderecoEstagio')->nullable();
            $table->string('localidadeEnderecoEstagio')->nullable();
            $table->string('ufEnderecoEstagio', 2)->nullable();  
            $table->integer('facebookEstagio');
            $table->integer('instagramEstagio');
            $table->integer('twitterEstagio');
            $table->integer('wordEstagio');
            $table->integer('excelEstagio');
            $table->integer('powerPointEstagioEstagio');
            $table->integer('podcastEstagio');
            $table->integer('doodleEstagio');
            $table->string('facebookTextEstagio')->nullable();
            $table->string('instagramTextEstagio')->nullable();
            $table->string('twitterTextEstagio')->nullable();
            $table->string('linkedinTextEstagio')->nullable();                        
            $table->string('curriculoEstagio');
            $table->string('trabalhoEstagio');
            $table->json('idiomasEstagio');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('codigoEditalEstagio')->references('codigoEditalEstagio')->on('editais_estagios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estagios');
    }
}
