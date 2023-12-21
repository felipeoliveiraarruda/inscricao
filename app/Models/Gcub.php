<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;
use App\Models\TipoDocumento;
use App\Models\ArquivoGcub;
use Carbon\Carbon;
use Uspdev\Replicado\Posgraduacao;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gcub extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'gcub';
    protected $primaryKey = 'codigoGcub';

    protected $fillable = [
        'passaporteAluno',
        'dadosGcub',
    ];

    public static function matricula($pdf, $codigoGcub)
    {
        $gcub  = Gcub::find($codigoGcub);
        $dados = json_decode($gcub->dadosGcub);

        $pdf->setCabecalho('ppgem');

        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   

        $texto = "<p>Eu, {$dados->nomeAluno}, Passaporte {$gcub->passaporteAluno}, e-mail {$dados->emailAluno}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>REGULAR</b>, no Mestrado do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> na área de concentração: <b>97134 - Materiais Convencionais e Avançados</b>, nas <b>Disciplinas</b> abaixo listadas:</p>";

        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode('PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - PPGEM REQUERIMENTO DE PRIMEIRA MATRÍCULA REGULAR'), 1, 'C', true);
        $pdf->Ln();
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetFillColor(255,255,255);
        $pdf->WriteTag(190,8, utf8_decode($texto), 0, 'J');
        $pdf->Ln(5);
                    
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(40,  8, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
        $pdf->Cell(110, 8, utf8_decode('DISCIPLINA'), 1, 0, 'C', true);
        $pdf->Cell(40,  8, utf8_decode('Nº DE CRÉDITOS'), 1, 0, 'C', true);
        $pdf->Ln();    
        
        $pdf->SetFont('Arial', '', 10);

        foreach($dados->disciplinasGcub as $disciplina)
        {
            $temp = Posgraduacao::disciplina($disciplina);
            
            $pdf->Cell(40,  8, utf8_decode("{$temp['sgldis']}-{$temp['numseqdis']}"), 1, 0, 'C', false);
            $pdf->Cell(110, 8, utf8_decode(" {$temp['nomdis']}"), 1, 0, 'L', false);
            $pdf->Cell(40,  8, utf8_decode(" {$temp['numcretotdis']}"), 1, 0, 'C', false);
            $pdf->Ln();  
        }

        $pdf->Ln(5);
        $pdf->Cell(75,  8, utf8_decode('Lorena, _____/_____/_______'), 0, 0, 'L', false);
        $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  8, utf8_decode('____________________________________'), 0, 0, 'C', false);
        $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75,  5, utf8_decode(''), 0, 0, 'L', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode('Assinatura do Aluno'), 0, 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(75,  8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C', false);
        $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(75,  8, utf8_decode('Orientação Acadêmica'), 0, 0, 'C', false);
        $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75,  5, utf8_decode('Nome / Carimbo do Orientador ou'), 'T', 0, 'C', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode('Assinatura do Orientador'), 'T', 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->Cell(75,  5, utf8_decode('Orientador Acadêmico'), 0, 0, 'C', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln(20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'L', false);
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(80, 8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C', false);
        $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'L', false);
        $pdf->Cell(80, 5, utf8_decode('Coordenador do Programa'), 'T', 0, 'C', false);
        $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        //$pdf->Output();

        $temp = storage_path("app/public/gcub/{$codigoGcub}/requerimento.pdf");

        if (!file_exists($temp))
        {
            $pdf->Output('F', $temp);
        }
        
        $arquivos = ArquivoGcub::where('codigoGcub', $codigoGcub)->get();

        $oMerger = PDFMerger::init();

        $oMerger->addPDF($temp, 'all');
        
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
                $temp2 = storage_path("app/public/gcub/{$codigoGcub}/{$arquivo->codigoArquivo}_.pdf");
                shell_exec('ghostscript -dBATCH -dNOPAUSE -q -dCompatibilityLevel=1.4 -sDEVICE=pdfwrite -sOutputFile="'.$temp2.'" "'.$temp.'"');
                $oMerger->addPDF($temp2, 'all');                 
                Storage::delete($temp2);
            }
            else
            {
                $oMerger->addPDF($temp, 'all');
            }
        }

        $temp = storage_path("app/public/gcub/{$codigoGcub}/{$codigoGcub}.pdf");

        $oMerger->merge();
        $oMerger->save($temp);

        return "gcub/{$codigoGcub}/{$codigoGcub}.pdf";
    }

    public static function bolsista($pdf, $codigoGcub)
    {
        $gcub  = Gcub::find($codigoGcub);
        $dados = json_decode($gcub->dadosGcub);

        $pdf->setCabecalho('bolsista');

        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(3);

        for ($i = 0; $i < 3; $i++)
        {
            $pdf->Cell(190, 6, '', 0, 0, 'C');
            $pdf->Ln();
        }
        
        /* IES */
        $pdf->Cell(60, 6, '', 0, 0, 'C');
        $pdf->Cell(130, 6, utf8_decode('ESCOLA DE ENGENHARIA DE LORENA'), 0, 0, 'L');
        $pdf->Ln();
        
        /* Codigo IES */
        $pdf->Cell(132, 5, '', 0, 0, 'C');
        $pdf->Cell(53, 5, utf8_decode('33030014001'), 0, 0, 'L');
        $pdf->Ln();

        /* Programa e Código */
        $pdf->Cell(50, 9, '', 0, 0, 'C');
        $pdf->Cell(69, 9, utf8_decode('ENGENHARIA DE MATERIAS'), 0, 0, 'L');
        $pdf->Cell(13, 9, '', 0, 0, 'L');
        $pdf->Cell(58, 9, utf8_decode('33670013'), 0, 0, 'L');
        $pdf->Ln();

        /* Nivel */
        $mestrado  = '';
        $doutorado = '';

        if ($dados->tipoNivel = 'Mestrado')
        {
            $mestrado = 'X';
        }
        else if ($dados->tipoNivel == 'Doutorado')
        {
            $doutorado = 'X';
        }

        $pdf->Cell(46, 11, '', 0, 0, 'C');
        $pdf->Cell(5, 11, utf8_decode($mestrado), 0, 0, 'C');
        $pdf->Cell(27, 11, '', 0, 0, 'C');
        $pdf->Cell(5, 11, utf8_decode($doutorado), 0, 0, 'L');
        $pdf->Cell(26, 11, '', 0, 0, 'C');
        $pdf->Cell(5, 11, '', 0, 0, 'C');
        $pdf->Cell(76, 10, '', 0, 0, 'L');
        $pdf->Ln();

        /* Nome */
        $pdf->Cell(190, 6, '', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(46, 6, '', 0, 0, 'C');
        $pdf->Cell(144, 6, utf8_decode($dados->nomeAluno), 0, 0, 'L');
        $pdf->Ln();

        /* Data Nascimento */
        $dataNascimento = new Carbon($dados->dataNascimentoAluno);

        $pdf->Cell(64, 9, '', 0, 0, 'C');
        $pdf->Cell(27, 9, utf8_decode($dataNascimento->format('d/m/Y')), 0, 0, 'L');
        
        /* Sexo */
        $masculino = '';
        $feminino  = '';

        if ($dados->sexoAluno = 'Masculino')
        {
            $masculino = 'X';
        }
        else if ($dados->sexoAluno == 'Feminino')
        {
            $feminino = 'X';
        }

        $pdf->Cell(40, 9, '', 0, 0, 'C');
        $pdf->Cell(6, 9, utf8_decode($masculino), 0, 0, 'C');
        $pdf->Cell(21, 9, '', 0, 0, 'C');
        $pdf->Cell(6, 9, utf8_decode($feminino), 0, 0, 'C');
        $pdf->Cell(26, 9, '', 0, 0, 'C');
        $pdf->Ln();

        /* Nacionaliade */
        $nacional    = '';
        $estrangeiro = '';
        $visto       = '';

        if ($dados->nacionalidadeAluno == 1)
        {
            $nacional = 'X';
            $visto    = '';
        }
        else
        {
            $estrangeiro = 'X';
            $visto       = 'X';
        }

        $nacionalidade = Utils::obterPais($dados->nacionalidadeAluno);

        $pdf->Cell(59, 7, '', 0, 0, 'C');
        $pdf->Cell(6, 7, utf8_decode($nacional), 0, 0, 'C');
        $pdf->Cell(21, 7, '', 0, 0, 'C');
        $pdf->Cell(6, 7, utf8_decode($estrangeiro), 0, 0, 'C');
        $pdf->Cell(35, 7, '', 0, 0, 'C');
        $pdf->Cell(63, 7, '', 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(82, 9, '', 0, 0, 'C');
        $pdf->Cell(5, 9, utf8_decode($visto), 0, 0, 'C');
        $pdf->Cell(9, 9, '', 0, 0, 'C');
        $pdf->Cell(5, 9, '', 0, 0, 'L');
        $pdf->Cell(29, 9, '', 0, 0, 'C');
        
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(29, 9, utf8_decode($gcub->passaporteAluno), 0, 0, 'L');
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(31, 9, utf8_decode($nacionalidade['nompas']), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(190, 3, '', 0, 0, 'C');
        $pdf->Ln();

        /* Vinculo */
        $vinculo_nao = '';
        $vinculo_sim = '';

        if ($dados->vinculoEmpregaticio == "Sim")
        {
            $pdf->Cell(91, 6, '', 0, 0, 'C');
            $pdf->Cell(5, 6, 'X', 0, 0, 'C');
            $pdf->Cell(18, 6, '', 0, 0, 'C');
            $pdf->Cell(4, 6, '', 0, 0, 'C');
            $pdf->Cell(72, 6, '', 0, 0, 'C');
            $pdf->Ln();
            
            $pdf->Cell(190, 2, '', 0, 0, 'C');
            $pdf->Ln();
            
            /* Tipo Empregador */
            $ies_pais     = '';
            $ies_exterior = '';
            $empresa      = '';

            if ($dados->tipoEmpregador == "Instituição de Ensino Superior no país")
            {
                $ies_pais = 'X';
            }
            else if ($dados->tipoEmpregador == "Instituição de Ensino Superior no exterior")
            {
                $ies_exterior = 'X';
            }
            else
            {
                $empresa = 'X';
            }
            
            $pdf->Cell(91, 6, '', 0, 0, 'C');
            $pdf->Cell(5, 6,  $ies_pais, 0, 0, 'C');
            $pdf->Cell(22, 6, '', 0, 0, 'C');
            $pdf->Cell(5, 6, $ies_exterior, 0, 0, 'C');
            $pdf->Cell(27, 6, '', 0, 0, 'C');
            $pdf->Cell(5, 6, $empresa, 0, 0, 'L');
            $pdf->Cell(35, 6, '', 0, 0, 'C');
            $pdf->Ln();

            $pdf->Cell(190, 2, '', 0, 0, 'C');
            $pdf->Ln();
            
            /* Empregador */
            $pdf->Cell(60, 6, '', 0, 0, 'L');
            $pdf->Cell(130, 6, utf8_decode($dados->nomeEmpregador), 0, 0, 'L');
            $pdf->Ln();
    
            $pdf->Cell(190, 2, '', 0, 0, 'C');
            $pdf->Ln();
            
            /* Tipo de Afastamento */
            $integral      = '';
            $parcial       = '';
            $nao_informado = '';

            if ($dados->tipoAfastamento == "Integral")
            {
                $integral = 'X';
            }
            else if ($dados->tipoEmpregador == "Parcial")
            {
                $parcial = 'X';
            }
            else
            {
                $nao_informado = 'X';
            }

            $pdf->Cell(78, 5, '', 0, 0, 'L');
            $pdf->Cell(4, 5, $integral, 0, 0, 'C');
            $pdf->Cell(18, 5, '', 0, 0, 'L');
            $pdf->Cell(5, 5, $parcial, 0, 0, 'C');
            $pdf->Cell(18, 5, '', 0, 0, 'L');
            $pdf->Cell(4, 5, $nao_informado, 0, 0, 'C');
            $pdf->Cell(63, 5, '', 0, 0, 'L');
            $pdf->Ln();

            /* Categoria funcional */
            $docente      = '';
            $nao_docente   = '';

            if ($dados->categoriaFuncional == "Docente")
            {
                $docente = 'X';
            }
            else
            {
                $nao_docente = 'X';
            }

            $com_salario = '';
            $sem_salario = '';

            if ($dados->situacaoSalarial == "Com salário")
            {
                $com_salario = 'X';
            }
            else
            {
                $sem_salario = 'X';
            }
                
            $pdf->Cell(190, 3, '', 0, 0, 'C');
            $pdf->Ln();

            $pdf->Cell(69, 5, '', 0, 0, 'L');
            $pdf->Cell(4, 5, $docente, 0, 0, 'C');
            $pdf->Cell(14, 5, '', 0, 0, 'L');
            $pdf->Cell(4, 5, $nao_docente, 0, 0, 'C');
            $pdf->Cell(18, 5, '', 0, 0, 'L');            
            $pdf->Cell(36, 5, '', 0, 0, 'L');
            $pdf->Cell(5, 5, $com_salario, 0, 0, 'C');
            $pdf->Cell(13, 5, '', 0, 0, 'L');
            $pdf->Cell(5, 5, $sem_salario, 0, 0, 'C');
            $pdf->Cell(22, 5, '', 0, 0, 'L');
            $pdf->Ln();

            $pdf->Cell(190, 3, '', 0, 0, 'C');
            $pdf->Ln();

            $pdf->Cell(73, 5, '', 0, 0, 'L');
            $pdf->Cell(50, 5, utf8_decode($dados->tempoServico), 0, 0, 'L');
            $pdf->Cell(22, 5, '', 0, 0, 'L');
            $pdf->Cell(23, 5, utf8_decode($dados->tempoServicoMesAno), 0, 0, 'L');
            $pdf->Cell(22, 5, '', 0, 0, 'L');
            $pdf->Ln();
        }
        else
        {
            $pdf->Cell(91, 6, '', 0, 0, 'C');
            $pdf->Cell(5, 6, '', 0, 0, 'C');
            $pdf->Cell(18, 6, '', 0, 0, 'C');
            $pdf->Cell(4, 6, 'X', 0, 0, 'C');
            $pdf->Cell(72, 6, '', 0, 0, 'C');
            $pdf->Ln();

            $pdf->Cell(190, 2, '', 0, 0, 'C');
            $pdf->Ln(39);
        }

        /* Ultima Titulação */
        $paisTitulacao = Utils::obterPais($dados->codigoPaisTitulacao);

        $pdf->Cell(190, 5, '', 0, 0, 'C');
        $pdf->Ln();
        
        $pdf->Cell(78, 6, '', 0, 0, 'L');
        $pdf->Cell(58, 6, utf8_decode($dados->nivelTitulacao), 0, 0, 'L');
        $pdf->Cell(27, 6, '', 0, 0, 'L');
        $pdf->Cell(18, 6, utf8_decode($dados->anoTitulacao), 0, 0, 'L');
        $pdf->Cell(9, 6, '', 0, 0, 'L');
        $pdf->Ln();

        /* IES Titulacao */
        $pdf->Cell(190, 2, '', 0, 0, 'C');
        $pdf->Ln();

        /* 138 caracteres */
        //$tamanho = (int)Str::of($dados->iesTitulacao)->length();

        $tamanho = (int)Str::of($dados->iesTitulacao)->length();

        if ($tamanho > 75)
        {
            $resto = $tamanho - 75;

            $parte1 = Str::substr($dados->iesTitulacao, 0, 75);
            $parte2 = Str::substr($dados->iesTitulacao, 75, 136);
        }
        else
        {
            $parte1 = $dados->iesTitulacao;
            $parte2 = '';
        }

        /* Até 75 caractecres */
        $pdf->Cell(59, 5, '', 0, 0, 'L');
        $pdf->Cell(122, 5, utf8_decode($parte1), 0, 0, 'L');
        $pdf->Cell(9, 5, '', 0, 0, 'L');
        $pdf->Ln();

        /* Até 61 caractecres */
        $pdf->Cell(32, 6, '', 0, 0, 'L');
        $pdf->Cell(95, 6, utf8_decode($parte2), 0, 0, 'L');
        $pdf->Cell(9, 6, '', 0, 0, 'L');
        $pdf->Cell(54, 6, utf8_decode($paisTitulacao['nompas']), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Output();
    }
}
