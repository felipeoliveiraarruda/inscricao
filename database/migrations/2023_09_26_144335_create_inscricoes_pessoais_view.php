<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesPessoaisView extends Migration
{
    public function up()
    {
        \DB::statement($this->dropView());
        \DB::statement($this->createView());
    }

    public function down()
    {
        \DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return "CREATE VIEW VW_INSCRICOES_ARQUIVOS AS
                (SELECT I.*,
                    A.codigoArquivo, A.codigoTipoDocumento, A.linkArquivo, A.tipoDocumento
                FROM inscricoes I JOIN `inscricoes_arquivos` IA ON I.codigoInscricao = IA.codigoInscricao
                                JOIN `VW_ARQUIVOS` A ON IA.codigoArquivo = A.codigoArquivo);";
    }
       
    private function dropView(): string
    {
        return "DROP VIEW IF EXISTS VW_INSCRICOES_ARQUIVOS";
    }
}
