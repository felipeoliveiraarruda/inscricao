<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoAreaTablePae extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pae', function (Blueprint $table) {
            $table->integer('codigoArea')
                  ->after('codigoCurso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pae', function (Blueprint $table) {
            $table->dropColumn('codigoArea');
        }); 
    }
}
