<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use App\Models\Inscricao;

class ZipController extends Controller
{
    public function createZip($codigoInscricao)
    {
        $zip = new ZipArchive;
        $inscricao    = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $obrigatorios = Inscricao::obterObrigatorioInscricao($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35));

        $zipFileName = "app/public/download/{$inscricao->name}.zip";

        $zip->open(storage_path($zipFileName), ZipArchive::CREATE);

        foreach($obrigatorios as $obrigatorio) 
        {
            $file     = storage_path("app/public/{$obrigatorio->linkArquivo}");
            dd($file);


            
            /*$extensao = pathinfo($file, PATHINFO_EXTENSION);
            $nome     = "{$obrigatorio->ordemTipoDocumento} {$obrigatorio->tipoDocumento}.{$extensao}";*/
            $zip->addFile($file, basename($file));
        }

        $zip->close();


        $zip_file = 'invoices.zip';

        // Initializing PHP class
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $invoice_file = 'invoices/AMZ-001.pdf';

        // Adding file: second parameter is what will the path inside of the archive
        // So it will create another folder called "storage/" inside ZIP, and put the file there.
        $zip->addFile(storage_path($invoice_file), $invoice_file);
        $zip->close();

        // We return the file immediately after download
        return response()->download($zip_file);



        return response()->download(storage_path($zipFileName));
    }
}
