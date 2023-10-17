<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNivelEditalTableEditais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editais', function (Blueprint $table) {
            $table->dropColumn('nivelEdital');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('editais', function (Blueprint $table) {
            $table->char('nivelEdital', 2)
                  ->after('codigoCurso');
        });
    }
}
