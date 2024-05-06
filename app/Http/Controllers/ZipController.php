<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Edital;
use File;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

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

    public function proficiencia_merge($codigoEdital)
    {
        $arquivos = Arquivo::select(\DB::raw('arquivos.*'))
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                            ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                            ->where('inscricoes.codigoEdital',  $codigoEdital)
                            ->where('inscricoes.statusInscricao',  'C')
                            ->orderBy('users.name')
                            ->get();

        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $curso       = Edital::obterCursoEdital($codigoEdital);

        if ($curso == 97002)
        {
            $documento = 'ppgem';
        }

        $oMerger = PDFMerger::init();

        foreach($arquivos as $arquivo)
        {
            $temp = storage_path("app/public/{$arquivo->linkArquivo}");
            
            $filepdf = fopen($temp,"r");

            if ($filepdf) 
            {
                $line_first = fgets($filepdf);
                fclose($filepdf);
            } 
            else
            {
                echo "error opening the file.";
            }
            
            // extract number such as 1.4 ,1.5 from first read line of pdf file
            preg_match_all('!\d+!', $line_first, $matches);	
           
            // save that number in a variable
            $pdfversion = implode('.', $matches[0]);
            
            if($pdfversion > "1.4")
            {
                $temp2 = storage_path("app/public/proficiencia/{$arquivo->codigoArquivo}_.pdf");
                shell_exec('ghostscript -dBATCH -dNOPAUSE -q -dCompatibilityLevel=1.4 -sDEVICE=pdfwrite -sOutputFile="'.$temp2.'" "'.$temp.'"');
                $oMerger->addPDF($temp2, 'all');                 
                Storage::delete($temp2);
            }
            else
            {
                $oMerger->addPDF($temp, 'all');
            }
        }

        $oMerger->merge();
        $oMerger->save(storage_path("app/public/proficiencia/{$anosemestre}/{$documento}.pdf"));

        return redirect(asset("storage/proficiencia/{$anosemestre}/{$documento}.pdf"));
    }
}
