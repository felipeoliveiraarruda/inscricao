<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricaoTotalView extends Migration
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
        return "CREATE VIEW VW_INSCRICAO_TOTAL AS
                (SELECT I.*,
                    COUNT(A.codigoInscricao) AS arquivos,
                    COUNT(D.codigoInscricao) AS documentos,
                    COUNT(E.codigoInscricao) AS enderecos,
                    COUNT(P.codigoInscricao) AS dados_pessoais
                FROM inscricoes I LEFT JOIN `inscricoes_arquivos` A ON I.codigoInscricao = A.codigoInscricao
                                LEFT JOIN `inscricoes_documentos` D ON I.codigoInscricao = D.codigoInscricao
                                LEFT JOIN `inscricoes_enderecos` E ON I.codigoInscricao = E.codigoInscricao
                                LEFT JOIN `inscricoes_pessoais` P ON I.codigoInscricao = P.codigoInscricao
                GROUP BY I.codigoInscricao);";
    }
       
    private function dropView(): string
    {
        return "DROP VIEW IF EXISTS VW_INSCRICAO_TOTAL";
    }
}
