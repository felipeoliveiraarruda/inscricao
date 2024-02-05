<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\InscricoesResumoEscolar;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;
use Carbon\Carbon;
use Mail;
use App\Mail\MatriculaMail;

class ImpressaoController extends Controller
{
    public function primeira_matricula($codigoInscricao, \App\Models\Pdf\Matricula $pdf)
    {
        $edital = Edital::obterEditalInscricao($codigoInscricao);

        if ($edital->codigoCurso == 97002)
        {
            $anexo = Inscricao::gerarMatricula($pdf, 'ppgem', $codigoInscricao);
        }
        
        Mail::to($edital->email)->send(new MatriculaMail($codigoInscricao, $anexo));
        //Mail::to('dev.ci.eel@usp.br')->send(new MatriculaMail($codigoInscricao, $anexo));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento de Primeira Matrícula. Tente novamente mais tarde.");
        }    
        else
        {
            request()->session()->flash('alert-success', 'Requerimento de Primeira Matrícula cadastrado com sucesso. Aguarde informações no seu e-mail cadastrado.'); 
        } 
    
        return redirect('dashboard');
    }

    public function declaracao_acumulos($codigoInscricao, \App\Models\Pdf\DeclaracaoAcumulo $pdf)
    {

    }

    public function termo_compromisso($codigoInscricao, \App\Models\Pdf\TermoCompromisso $pdf)
    {
        $dados  = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $nivel  = Edital::obterNivelEdital($dados->codigoEdital);
        $codigo = Edital::obterCursoEdital($dados->codigoEdital);
        $curso  = Utils::obterCurso($codigo);

        if ($nivel == 'ME')
        {
            $nivel = 'Mestrado';
        }

        $pdf->setCabecalho('capes');

        $pdf->SetStyle('p', 'arial', 'N', 10, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');

        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   
        $pdf->AddPage();

        $texto = "Declaro, para os devidos fins, que eu, {$dados->name}, CPF {$dados->cpf}, aluno (a) devidamente matriculado (a) na Escola de Engenharia de Lorena no Programa de Pós-Graduação {$curso['nomcur']} sob o número de matrícula 99999999, em nível de {$nivel}, tenho ciência das obrigações inerentes à qualidade de beneficiário de bolsa, conforme regulamento vigente do Programa de Demanda Social - DS, anexo à Portaria nº 76, de 14 de abril de 2010, e da Portaria nº 133, de 10 de julho de 2023, e nesse sentido, COMPROMETO-ME a respeitar as seguintes cláusulas:";
            
        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode($texto), 0, 'J'); 
        $pdf->Ln(3);       

        $pdf->SetFont('Arial', '', 10);
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>I -</b> dedicar-me integralmente às atividades do Programa de Pós-Graduação;</i></p>"), 0, 'J');        
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>II -</b> comprovar desempenho acadêmico satisfatório, consoante às normas definidas pela instituição promotora do curso;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>III -</b> realizar estágio de docência de acordo com o estabelecido no art. 18 do regulamento vigente;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>VI -</b> ser classificado no processo seletivo especialmente instaurado pela Instituição de Ensino Superior em que realiza o curso;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>V -</b> apresentar Declaração de Acúmulo para informar eventuais, bolsas, vínculos empregatícios ou outros rendimentos e obter autorização da Instituição de Ensino Superior ou do Programa de Pós-Graduação, antes do início da vigência da bolsa;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>VI-</b> informar à coordenação do Programa de Pós-Graduação, por meio de Declaração de Acúmulo, qualquer alteração referente a acúmulos de bolsas, vínculos empregatícios ou outros rendimentos, para fins de atualização das informações na plataforma de concessão e acompanhamento de bolsas;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>VII - </b>não acumular bolsa de mestrado e doutorado no País com outras bolsas, nacionais e internacionais, de mesmo nível, financiadas com recursos públicos federais;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>VIII -</b> citar a Coordenação de Aperfeiçoamento de Pessoal de Novel Superior - CAPES em trabalhos produzidos e publicados em qualquer mídia, que decorram de atividades financiadas, integral ou parcialmente, pela referida Fundação, conforme art. 1º da Portaria nº 206, de 4 de setembro de 2018;</i></p>"), 0, 'J');
        $pdf->Ln();
        $pdf->WriteTag(190, 5, utf8_decode("<p><i><b>IX -</b> assumir a obrigação de restituir os valores despendidos com bolsa, na hipótese de interrupção do estudo, salvo se motivada por caso fortuito, força maior, circunstância alheia à vontade ou doença grave devidamente comprovada.</i></p>"), 0, 'J');
        $pdf->Ln(3);

        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode('A inobservância das cláusulas citadas acima, ou se praticada qualquer fraude pelo(a) beneficiário, implicará no cancelamento da bolsa, com a restituição integral e imediata dos recursos, atualizados de acordo com os índices previstos em lei competente, acarretando ainda, a impossibilidade de receber benefícios por parte da CAPES, pelo período de 5 (cinco) anos, contados do conhecimento do fato.'), 0, 'J');
        $pdf->Ln();

        $pdf->Output();
    }
}
