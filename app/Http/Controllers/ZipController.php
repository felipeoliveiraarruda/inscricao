<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Inscricao;
use File;

class ZipController extends Controller
{
    public function createZip($codigoInscricao)
    {
        $zip = new ZipArchive;
        $inscricao    = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $obrigatorios = Inscricao::obterObrigatorioInscricao($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $zipFileName = "app/public/download/{$inscricao->name}.rar";
        $diretorio   = storage_path("app/public/download/{$inscricao->name}");

        if (!is_dir($diretorio))
        {
            File::makeDirectory($diretorio, 0777, true);
        }
        
        foreach($obrigatorios as $obrigatorio) 
        {
            $file = storage_path("app/public/{$obrigatorio->linkArquivo}");
            $extensao = pathinfo($file, PATHINFO_EXTENSION);
            $nome     = "{$obrigatorio->ordemTipoDocumento} {$obrigatorio->tipoDocumento}.{$extensao}";
            File::copy($file, "{$diretorio}/{$nome}");
        }
        
        $zip->open(storage_path($zipFileName), ZipArchive::CREATE);

        $options = array('add_path' => ' ', 'remove_all_path' => TRUE);

        $zip->addGlob("{$diretorio}/*.*", GLOB_BRACE, $options);

        $zip->close();

        File::deleteDirectory($diretorio);

        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }
}
