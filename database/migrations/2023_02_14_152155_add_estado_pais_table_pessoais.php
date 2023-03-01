<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoPaisTablePessoais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoais', function (Blueprint $table) {
            $table->string('estadoPessoal', 50)->nullable()->after('naturalidadePessoal');
            $table->string('paisPessoal', 50)->nullable()->after('estadoPessoal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoais', function (Blueprint $table) {
            $table->dropColumn('estadoPessoal');
            $table->dropColumn('paisPessoal');
        });   
    }
}
