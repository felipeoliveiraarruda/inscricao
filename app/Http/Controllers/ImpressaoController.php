<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        $anexo = '';

        if ($edital->codigoCurso == 97002)
        {
            Inscricao::gerarMatricula($pdf, 'ppgem', $codigoInscricao);
            //$anexo = Inscricao::gerarMatricula($pdf, 'ppgem', $codigoInscricao);
        }

        /*if ($edital->codigoCurso == 97004)
        {
            $anexo = Inscricao::gerarMatricula($pdf, 'ppgpe', $codigoInscricao);
        }*/
        
        //Mail::to($edital->email)->send(new MatriculaMail($codigoInscricao, $anexo));
        /*Mail::to('dev.ci.eel@usp.br')->send(new MatriculaMail($codigoInscricao, $anexo));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento de Primeira Matrícula. Tente novamente mais tarde.");
        }    
        else
        {
            request()->session()->flash('alert-success', 'Requerimento de Primeira Matrícula cadastrado com sucesso. Aguarde informações no seu e-mail cadastrado.'); 
        }
    
        return redirect('dashboard');*/
    }

    public function gerar_primeira_matricula($codigoEdital)
    {
        $deferidos = DB::table('VW_DEFERIMENTOS')
                       ->where('codigoEdital', $codigoEdital)
                       ->where('statusInscricao', 'C')
                       ->where('statusDisciplina', 'D')
                       ->orderByRaw('codigoDisciplina, name')
                       ->get();

        foreach($deferidos as $deferido)
        {
            $pdf = new \App\Models\Pdf\Matricula;
            ImpressaoController::primeira_matricula($deferido->codigoInscricao, $pdf);
        }

    }

    public function declaracao_acumulos($codigoInscricao, \App\Models\Pdf\DeclaracaoAcumulo $pdf)
    {
        $dados  = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $nivel  = Edital::obterNivelEdital($dados->codigoEdital);
        $codigo = Edital::obterCursoEdital($dados->codigoEdital);
        $curso  = Utils::obterCurso($codigo);
        
        $matricula = $dados->codpes;
        
        if ($codigoInscricao == 402)
        {
            $matricula = 11797532;
        }

        if ($codigoInscricao == 519)
        {
            $matricula = 11014211;
        }

        if ($codigoInscricao == 569)
        {
            $matricula = 16912942;
        }

        if ($codigoInscricao == 567)
        {
            $matricula = 16912935;
        }


        if ($nivel == 'ME' || $nivel == 'MF')
        {
            $nivel = 'Mestrado';
        }

        if ($nivel == 'DD' || $nivel == 'DF')
        {
            $nivel = 'Doutorado';
        }

        $pdf->SetStyle('p', 'arial', 'N', 10, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');

        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   
        $pdf->AddPage();

        $pdf->Image(asset("images/cabecalho/capes.png"), 25, 10, 25);
        $pdf->SetFont('Arial', 'B', 14);
        
        $pdf->Ln(8);
        $pdf->Cell(190, 8, utf8_decode(Str::upper('Declaração de Acúmulos')), 0, 0, "C");
        $pdf->Ln();
        $pdf->Cell(190, 8, '', 0, 0, "C");
        $pdf->Ln(10);

        $texto = "Declaro, para os devidos fins, que eu, {$dados->name}, CPF {$dados->cpf}, aluno (a) devidamente matriculado (a) na Escola de Engenharia de Lorena no Programa de Pós-Graduação {$curso['nomcur']} sob o número de matrícula {$matricula}, em nível de {$nivel}, em atenção à Portaria no 133, de 10 de julho de 2023, informo que possuo vínculo empregatício ou outros rendimentos, conforme declarado abaixo:";        
            
        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode($texto), 0, 'J'); 
        $pdf->Ln(3); 

        $pdf->SetFont('Times', '', 12);  
        $pdf->Cell(65, 8, utf8_decode("(   ) Cadastramento de bolsa"), 0, 'J');   
        $pdf->Cell(77, 8, utf8_decode("(   ) Atualização de bolsa Processo SCBA nº"), 0, 'J');    
        $pdf->Cell(48, 8, utf8_decode(""), 'B', 'J');   
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(190, 8, utf8_decode("1- Atividades Remuneradas:"), 0, 'J');        
        $pdf->Ln();

        $total = 4;

        for($i = 1; $i < $total; $i++)
        {
            $pdf->SetFont('Times', 'B', 11);
            $pdf->SetFillColor(190, 190, 190);
            $pdf->Cell(190, 8, utf8_decode("Tipo de Vínculo {$i}"), 1, 0, 'L', true);
            $pdf->Ln();

            $pdf->SetFont('Times', '', 11);
            $pdf->CellFitScale(20, 8, utf8_decode("(   ) CLT"), 1, 0, 'C', false);
            $pdf->CellFitScale(35, 8, utf8_decode("(   ) Pessoa Jurídica"), 1, 0, 'C', false);
            $pdf->CellFitScale(40, 8, utf8_decode("(   ) Regime Jurídico Único"), 1, 0, 'L', false);
            $pdf->CellFitScale(38, 8, utf8_decode("(   ) Temporário Lei 6.019/74"), 1, 0, 'L', false);
            $pdf->CellFitScale(57, 8, utf8_decode("(   ) Contrato por prazo determinado Lei 9.601/98"), 1, 0, 'L', false);
            $pdf->Ln();

            $pdf->Cell(45, 8, utf8_decode("Início da Atividade:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'L', false);
            $pdf->Cell(45, 8, utf8_decode("Fim da Atividade:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'L', false);
            $pdf->Ln();

            $pdf->Cell(45, 8, utf8_decode("Seção CNAE*:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'L', false);
            $pdf->Cell(45, 8, utf8_decode("Divisão CNAE*:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'l', false);
            $pdf->Ln();

            if ($i == $total - 1)
            {
                $pdf->Cell(190, 8, utf8_decode("* Utilizar nº CNAE anexo"), 0, 0, 'L', false);
                $pdf->Ln(5);
            }

            $pdf->Ln(5);
        }

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(190, 8, utf8_decode("2- Outros Rendimentos"), 0, 'J');        
        $pdf->Ln();

        $pdf->Cell(190, 8, utf8_decode("Informar os outros rendimentos que possui:"), 1, 0, 'L', true);
        $pdf->Ln();

        for($i = 1; $i < $total; $i++)
        {
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(190, 8, utf8_decode("{$i}-"), 1, 0, 'L', false);
            $pdf->Ln();

            $pdf->Cell(45, 8, utf8_decode("Início da Atividade:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'L', false);
            $pdf->Cell(45, 8, utf8_decode("Fim da Atividade:"), 1, 0, 'L', true);
            $pdf->Cell(50, 8, '', 1, 0, 'L', false);
            $pdf->Ln();
        }

       /* $pdf->SetY(-30);
        $pdf->Cell(0, 9, $pdf->PageNo().'/{nb}', 0, 0, 'R');*/

        $pdf->AddPage();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(190, 8, utf8_decode("3- Bolsas Declaratórias"), 0, 'J');        
        $pdf->Ln();

        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode(" - Acumulará essa bolsa Capes com outra bolsa, nacional ou internacional, de mesmo nível, financiada com recursos públicos federais?"), 0, 'J'); 
        $pdf->Ln(1);

        $pdf->Cell(5, 8, utf8_decode(""), 0, 'J');  
        $pdf->Cell(185, 8, utf8_decode("(   ) Sim  (   ) Não"), 0, 'J');        
        $pdf->Ln();

        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode(" - Acumulará essa bolsa Capes com outra bolsa, nacional ou internacional, cuja legislação vigente vede expressamente o acúmulo?"), 0, 'J'); 
        $pdf->Ln(1);

        $pdf->Cell(5, 8, utf8_decode(""), 0, 'J');  
        $pdf->Cell(185, 8, utf8_decode("(   ) Sim  (   ) Não"), 0, 'J');        
        $pdf->Ln();

        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode(" - Acumulará essa bolsa Capes com outra bolsa, nacional ou internacional, de mesmo nível, financiada com recursos não federais?"), 0, 'J'); 
        $pdf->Ln(1);

        $pdf->Cell(5, 8, utf8_decode(""), 0, 'J');  
        $pdf->Cell(185, 8, utf8_decode("(   ) Sim  (   ) Não"), 0, 'J');        
        $pdf->Ln();

        $pdf->SetFont('Times', '', 12);
        $pdf->MultiCell(190, 8, utf8_decode(" - Acumulará essa bolsa Capes com outra bolsa, nacional ou internacional, que não seja de mesmo nível?"), 0, 'J'); 
        $pdf->Ln(1);

        $pdf->Cell(5, 8, utf8_decode(""), 0, 'J');  
        $pdf->Cell(185, 8, utf8_decode("(   ) Sim  (   ) Não"), 0, 'J');        
        $pdf->Ln(50);

        $pdf->SetFont('Arial', '', 10);
        $pdf->WriteTag(22, 5, utf8_decode('<i>Local e data:</i>'), 0, 'J');
        $pdf->Cell(168, 8, '', 'T', 0, 'C');
        $pdf->Ln();
        $pdf->WriteTag(62, 5, utf8_decode('<i>Assinatura do(a) beneficiário da bolsa:</i>'), 0, 'J');
        $pdf->Cell(128, 8, '', 'T', 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(190, 8, utf8_decode("(   ) Os acúmulos registrados acima estão de acordo com os critérios de acúmulo previstos no regulamento da Instituição de ensino e pesquisa ou PPG."), 0, 'J'); 
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $pdf->WriteTag(190, 15, utf8_decode('<i>Coordenador(a) do Programa de Pós-Graduação</i>'), 0, 'C');
        $pdf->Ln();
            
        $pdf->Cell(55, 10, '', 0, 0, 'C');
        $pdf->Cell(80, 10, '', 'B', 0, 'C');
        $pdf->Cell(55, 10, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->WriteTag(190, 8, utf8_decode('<i>Carimbo e assinatura</i>'), 0, 'C');

        /*$pdf->SetY(-30);
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(0, 9, $pdf->PageNo().'/{nb}', 0, 0, 'R');*/

        $pdf->Output();
    }

    public function termo_compromisso($codigoInscricao, \App\Models\Pdf\TermoCompromisso $pdf)
    {
        $dados  = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $nivel  = Edital::obterNivelEdital($dados->codigoEdital);
        $codigo = Edital::obterCursoEdital($dados->codigoEdital);
        $curso  = Utils::obterCurso($codigo);

        $matricula = $dados->codpes;
        
        if ($codigoInscricao == 402)
        {
            $matricula = 11797532;
        }

        if ($codigoInscricao == 519)
        {
            $matricula = 11014211;
        }

        if ($codigoInscricao == 569)
        {
            $matricula = 16912942;
        }

        if ($codigoInscricao == 567)
        {
            $matricula = 16912935;
        }

        if ($nivel == 'ME' || $nivel == 'MF')
        {
            $nivel = 'Mestrado';
        }

        if ($nivel == 'DD' || $nivel == 'DF')
        {
            $nivel = 'Doutorado';
        }

        $pdf->setCabecalho('capes');

        $pdf->SetStyle('p', 'arial', 'N', 10, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');

        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   
        $pdf->AddPage();

        $texto = "Declaro, para os devidos fins, que eu, {$dados->name}, CPF {$dados->cpf}, aluno (a) devidamente matriculado (a) na Escola de Engenharia de Lorena no Programa de Pós-Graduação {$curso['nomcur']} sob o número de matrícula {$matricula}, em nível de {$nivel}, tenho ciência das obrigações inerentes à qualidade de beneficiário de bolsa, conforme regulamento vigente do Programa de Demanda Social - DS, anexo à Portaria nº 76, de 14 de abril de 2010, e da Portaria nº 133, de 10 de julho de 2023, e nesse sentido, COMPROMETO-ME a respeitar as seguintes cláusulas:";
            
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

    public function cadastamento_bolsista(\App\Models\Pdf\Bolsista $pdf, $codigoInscricao)
    {
        $inscricao    = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $edital       = Edital::obterEditalInscricao($codigoEdital);
      
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
        $mestrado = '';
        $doutorado = 'X';

        /*if ($edital->codigoNivel == 4)
        {
            $mestrado = 'X';
            $doutorado = '';
            echo 'Mestrado';
            exit;
        }

        if ($edital->codigoNivel == 2)
        {
            $mestrado = '';
            $doutorado = 'X';
            echo 'Doutorado';
            exit;
        }
        
        if ($edital->codigoNivel == 3)
        {
            $mestrado = '';
            $doutorado = 'X';
            echo 'Doutorado Fluxo';
            exit;
        }*/

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
        $pdf->Cell(144, 6, utf8_decode($inscricao->name), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(64, 9, '', 0, 0, 'C');
        $pdf->Cell(27, 9, utf8_decode($inscricao->dataNascimentoPessoal->format('d/m/Y')), 0, 0, 'L');
        
        /* Sexo */
        $masculino = '';
        $feminino  = '';

        if ($inscricao->sexoPessoal == 'Masculino')
        {
            $masculino = 'X';
        }
        
        if ($inscricao->sexoPessoal == 'Feminino')
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

        if ($inscricao->paisPessoal == 1)
        {
            $nacional = 'X';
            $visto    = '';
            $passaporte  = '';
        }
        else
        {
            $estrangeiro = 'X';
            $visto       = '';
            $passaporte  = $inscricao->numeroDocumento;
        }

        $nacionalidade = Utils::obterPais($inscricao->paisPessoal);

        $pdf->Cell(59, 7, '', 0, 0, 'C');
        $pdf->Cell(6, 7, utf8_decode($nacional), 0, 0, 'C');
        $pdf->Cell(21, 7, '', 0, 0, 'C');
        $pdf->Cell(6, 7, utf8_decode($estrangeiro), 0, 0, 'C');
        $pdf->Cell(35, 7, '', 0, 0, 'C');
        $pdf->Cell(63, 7, $inscricao->cpf, 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(82, 9, '', 0, 0, 'C');
        $pdf->Cell(5, 9, utf8_decode($visto), 0, 0, 'C');
        $pdf->Cell(9, 9, '', 0, 0, 'C');
        $pdf->Cell(5, 9, '', 0, 0, 'L');
        $pdf->Cell(30, 9, '', 0, 0, 'C');
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(28, 9, $passaporte, 0, 0, 'L');
        
        $pdf->SetFont('Arial', '', 10);

        if ($inscricao->paisPessoal == 218)
        {
            $pdf->Cell(31, 9, utf8_decode('EAU'), 0, 0, 'L');
        }
        else
        {
            $pdf->Cell(31, 9, utf8_decode($nacionalidade['nompas']), 0, 0, 'L');
        }

        $pdf->Ln();

        $pdf->Cell(190, 3, '', 0, 0, 'C');
        $pdf->Ln();

        /* Vinculo */
        $vinculo_nao = '';
        $vinculo_sim = '';
        $vinculoEmpregaticio = 'Não';

        if ($vinculoEmpregaticio == "Sim")
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
        $paisTitulacao = Utils::obterPais($inscricao->paisPessoal);
        
        $resumo = Inscricao::obterEscolarInscricao($codigoInscricao);

        if ($resumo[0]->tipoResumoEscolar == '')
        {
            $maior = 'Graduação';
        }
        else
        {
            $maior = $resumo[0]->tipoResumoEscolar;
        }

        //$maior = 'Mestrado';

        $pdf->Cell(190, 5, '', 0, 0, 'C');
        $pdf->Ln();
        
        $pdf->Cell(78, 6, '', 0, 0, 'L');
        $pdf->Cell(58, 6, utf8_decode($maior), 0, 0, 'L');
        $pdf->Cell(27, 6, '', 0, 0, 'L');

        
        $pdf->Cell(18, 6, utf8_decode($resumo[0]->finalResumoEscolar->format('Y')), 0, 0, 'L');
        //$pdf->Cell(18, 6, utf8_decode('2024'), 0, 0, 'L');
        $pdf->Cell(9, 6, '', 0, 0, 'L');
        $pdf->Ln();

        /* IES Titulacao */
        $pdf->Cell(190, 2, '', 0, 0, 'C');
        $pdf->Ln();

        /* 138 caracteres */
        //$tamanho = (int)Str::of($dados->iesTitulacao)->length();

        $tamanho = (int)Str::of($resumo[0]->escolaResumoEscolar)->length();

        if ($tamanho > 75)
        {
            $pdf->SetFont('Arial', '', 8);
            $resto = $tamanho - 75;

            $parte1 = Str::substr($resumo[0]->escolaResumoEscolar, 0, 75);
            $parte2 = Str::substr($resumo[0]->escolaResumoEscolar, 75, 136);
        }
        else
        {
            $pdf->SetFont('Arial', '', 10);
            $parte1 = $resumo[0]->escolaResumoEscolar;
            $parte2 = '';
        }

        
        /* Até 75 caractecres */
        $pdf->Cell(59, 5, '', 0, 0, 'L');
        $pdf->Cell(122, 5, utf8_decode($parte1), 0, 0, 'L');
        $pdf->Cell(9, 5, '', 0, 0, 'L');
        $pdf->Ln();

        /* Até 61 caractecres */
        $pdf->Cell(32, 5, '', 0, 0, 'L');
        $pdf->Cell(95, 5, utf8_decode($parte2), 0, 0, 'L');
        $pdf->Cell(9, 5, '', 0, 0, 'L');
        $pdf->Cell(54, 5, utf8_decode($paisTitulacao['nompas']), 0, 0, 'L');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        /* Dados Financeiros */
        $pdf->Cell(190, 4, '', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 4, '', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 6, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(46, 5, '', 0, 0, 'C');
        $pdf->Cell(54, 5, utf8_decode('Banco do Brasil'), 0, 0, 'L');
        $pdf->Cell(54, 5, utf8_decode('001'), 0, 0, 'L');
        $pdf->Cell(36, 5, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(190, 2.4, '', 0, 0, 'C');
        $pdf->Ln();

        $financeiro = Inscricao::obterFinanceiroInscricao($codigoInscricao);

        //dd($financeiro);

        $pdf->Cell(46, 5, '', 0, 0, 'C');
        $pdf->Cell(54, 5, utf8_decode($financeiro->localRecursoFinanceiro), 0, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode($financeiro->agenciaRecursoFinanceiro), 0, 0, 'L');
        $pdf->Cell(40, 5, utf8_decode($financeiro->contaRecursoFinanceiro), 0, 0, 'L');

        /*$pdf->Cell(54, 5, utf8_decode('Lorena/SP'), 0, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('0857-5'), 0, 0, 'L');
        $pdf->Cell(40, 5, utf8_decode('62821-2'), 0, 0, 'L');*/
        $pdf->Ln();

         /* Marcar Mestrado/24 meses */
        $pdf->Cell(190, 5.5, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(92, 6, '', 0, 0, 'C');
        $pdf->Cell(23, 6, utf8_decode('05/2025'), 0, 0, 'L');
        $pdf->Cell(44, 6, '', 0, 0, 'L');
        $pdf->Cell(31, 6, utf8_decode('03/2025'), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(190, 1.5, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(92, 6, '', 0, 0, 'C');
        $pdf->Cell(98, 6, utf8_decode('13 meses'), 0, 0, 'L');
        $pdf->Ln();


        /* Marcar Mestrado/24 meses */
        $pdf->Cell(190, 7.5, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(33, 6, '', 0, 0, 'C');
        $pdf->Cell(4, 6, utf8_decode($mestrado), 0, 0, 'L');
        $pdf->Cell(45, 6, '', 0, 0, 'C');
        $pdf->Cell(5, 6, utf8_decode($doutorado), 0, 0, 'C');
        $pdf->Cell(103, 6, '', 0, 0, 'C');
        $pdf->Ln();

        $pdf->Output();
    }

    public function certificado_proficiencia($codigoEdital)
    {
        $inscricoes = Inscricao::select(\DB::raw('users.*, inscricoes.codigoEdital, inscricoes_proficiencia.nivelProficiencia'))
                               ->join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                               ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                               ->where('inscricoes.codigoEdital',  $codigoEdital)
                               ->where('inscricoes_proficiencia.statusProficiencia', 'S')
                               ->get();
        
        $pdf = new \App\Models\Pdf\Proficiencia;
        
        foreach($inscricoes as $inscricao)
        {
            if ($inscricao->nivelProficiencia == 'ME')
            {
                $nivel = 'Mestrado';
            }
            else
            {
                $nivel = 'Doutorado';
            }

            $edital = Edital::find($inscricao->codigoEdital);
            $exame = Carbon::parse($edital->dataExameEdital)->locale('pt-BR');

            $hoje = Carbon::now()->locale('pt-BR');

            $pdf->SetStyle('p', 'arial', 'N', 10, '0,0,0');
            $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
            $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
            $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');

            $pdf->SetDisplayMode('real');
            $pdf->AliasNbPages();
            $pdf->AddPage('L');

            $pdf->SetFont('Arial', '', 22);
            $pdf->SetY(65);
            $pdf->WriteTag(280, 15, utf8_decode('<p>Certifico que o(a) aluno(a) <b>'.Str::upper($inscricao->name).' - N.º USP: '.$inscricao->codpes.'</b>, regularmente matriculado(a) no curso de '.$nivel.' do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> da Escola de Engenharia de Lorena - EEL/USP, foi <b>"APROVADO(A)"</b> no Exame de Proficiência em Língua Estrangeira - Inglês, realizado  no dia '.$exame->format('d').' de '.$exame->translatedFormat('F').' de '.$exame->format('Y').'.</p>'), 0, 'J');
            $pdf->Ln(10);

            $pdf->Cell(280, 8, utf8_decode('Lorena/SP, '.$hoje->format('d').' de '.$hoje->translatedFormat('F').' de '.$hoje->format('Y').'.'), 0, 0, 'C');
                
            $sigla   = Utils::obterSiglaCurso($edital->codigoCurso);
            $sigla   = Str::lower($sigla);

            /*$arquivo = storage_path("app/public/{$sigla}/proficiencia/{$inscricao->name}.pdf");
            $nome    = "{$sigla}/proficiencia/{$inscricao->name}.pdf";

            $pdf->Output('F', $arquivo);

            /*if (!file_exists($arquivo))
            {
                $pdf->Output('F', $arquivo);
            }
            
            echo $arquivo."<br/>";*/
        }

        $pdf->Output();
    }

    public function regulamentacao($codigoRegulamento)
    {
        $regulamento = \App\Models\RegulamentosUsers::join('regulamentos', 'regulamentos_users.codigoRegulamento', '=', 'regulamentos.codigoRegulamento')
                                                    ->where('regulamentos.codigoRegulamento', '=', $codigoRegulamento)
                                                    ->where('codigoUsuario', '=', Auth::user()->id)
                                                    ->first();

        if ($regulamento->statusRegulamento == 'S')
        {
            $opto       = "  X  ";
            $nao_opto   = "     ";
        }
        else
        {
            $opto       = "     ";
            $nao_opto   = "  X  ";
        }

        $pdf = new \App\Models\Pdf\Regulamentacao;

        $hoje = Carbon::now()->locale('pt-BR');

        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('b2', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 14);
        
        $pdf->Cell(190, 8, utf8_decode('Ao'), 0, 0, "L");
        $pdf->Ln(15);
        
        $pdf->Cell(190, 8, utf8_decode('Presidente da Comissão de Pós-Graduação da EEL'), 0, 0, "L");
        $pdf->Ln(20);

        $pdf->WriteTag(190, 10, utf8_decode('<p> Eu, '.Str::upper(Auth::user()->name).' Nº USP: '.Auth::user()->codpes.', aluno(a) regularmente matriculado(a) no Programa de Pós-Graduação em Projetos Educacionais de Ciências - PPGPE, <b>DECLARO</b> para os devidos fins que: </p>'), 0, 'J');
        $pdf->Ln(10);

        $pdf->WriteTag(190, 8, utf8_decode('<p>('.$opto.') <bu><i>OPTO</bu></i>            ('.$nao_opto.') <bu><i>NÃO OPTO</bu></i></p>'), 0, 'C');
        $pdf->Ln(10);

        $pdf->WriteTag(190, 10, utf8_decode('<p>pelo '.$regulamento->textoRegulamento.'.</p>'), 0, 'J');
        $pdf->Ln(20);

        $pdf->Cell(190, 8, utf8_decode('Lorena-SP, '.$hoje->format('d').'/'.$hoje->format('m').'/'.$hoje->format('Y').'.'), 0, 0, 'C');
        $pdf->Ln(20);

        $pdf->Cell(80, 8, utf8_decode(''), 'B', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(80, 8, utf8_decode(''), 'B', 0, 'C');
        $pdf->Ln();

        $pdf->Cell(80, 8, utf8_decode('Assinatura do Pós-Graduando'), 0, 0, 'C');
        $pdf->Cell(30, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(80, 8, utf8_decode('Assinatura do Orientador'), 0, 0, 'C');
        $pdf->Ln();

        $pdf->Output();
    }
}
