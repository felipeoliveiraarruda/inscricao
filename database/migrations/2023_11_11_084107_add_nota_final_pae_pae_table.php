<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotaFinalPaePaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pae', function (Blueprint $table) {
            $table->double('notaFinalPae', 8, 2)
                  ->after('resultadoPae');
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
            $table->dropColumn('notaFinalPae');
        }); 
    }
}
