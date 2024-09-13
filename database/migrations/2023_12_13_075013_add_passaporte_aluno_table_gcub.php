<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassaporteAlunoTableGcub extends Migration
{
    public function up()
    {
        Schema::table('gcub', function (Blueprint $table) {
            $table->string('passaporteAluno', 100)
                  ->after('codigoGcub');
        });
    }

    public function down()
    {
        Schema::table('gcub', function (Blueprint $table) {
            $table->dropColumn('passaporteAluno');
        }); 
    }
}
