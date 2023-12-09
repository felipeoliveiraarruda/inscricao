<?php

namespace App\Http\Controllers;

use App\Models\Gcub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Utils;
use App\Models\Pdf\Matricula;
use Carbon\Carbon;
use Uspdev\Replicado\Posgraduacao;

class GcubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo)
    {
        if (Auth::guest())
        {
            $logo = asset("images/cabecalho/presenca/logo_{$tipo}.jpg");

            if($tipo == 'ppgem')
            {
                $disciplinas = Posgraduacao::disciplinasOferecimento(97134);
            }

            $paises = Utils::listarPais();            

            return view('gcub.index', 
            [
                'logo'        => $logo,
                'paises'      => $paises,
                'sexos'       => Utils::obterDadosSysUtils('sexo'),
                'disciplinas' => $disciplinas
            ]);
        }
    }

    public function store(Request $request)
    {
        unset($request['_token']);
        unset($request['cadastrar']);

        $gcub = Gcub::create([
            'dadosGcub' => $request->collect()->toJson(),
        ]);

        request()->session()->flash('alert-success', 'Requerimento de Primeira Matrícula cadastrado com sucesso.');    
        return redirect("gcub/{$gcub->codigoGcub}");
    }

    public function show(\App\Models\Pdf\Matricula $pdf, $codigoGcub)
    {
        $gcub  = Gcub::find($codigoGcub);
        $dados = json_decode($gcub->dadosGcub);

        $pdf->setCabecalho('ppgem');

        $pdf->SetStyle('b','arial','B',0,'0,0,0');
        $pdf->SetStyle('bu','arial','BU',0,'0,0,0');        

        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   

        /*+"nomeAluno": "Felipe de Oliveira Arruda"
        +"emailAluno": "felipeoa@usp.br"
        +"nacionalidadeAluno": "1"
        +"passaporteAluno": "123456789"
        +"dataNascimentoAluno": "1983-05-07"
        +"codigoPaisAluno": "1"
        +"telefoneAluno": "(12) 99752-1182"
        +"sexoAluno": "Masculino"
        +"nivelTitulacao": "Tecnólogo"
        +"iesTitulacao": "Fatec Cruzeiro"
        +"disciplinasGcub":*/

        $texto = "Eu, {$dados->nomeAluno}, Passaporte {$dados->passaporteAluno}, e-mail {$dados->emailAluno}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>REGULAR</b>, no Mestrado do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> na área de concentração: <b>97134 - Materiais Convencionais e Avançados</b>, nas <b>Disciplinas</b> abaixo listadas:";

        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode('PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - PPGEM REQUERIMENTO DE PRIMEIRA MATRÍCULA REGULAR'), 1, 'C', true);
        $pdf->Ln();
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetFillColor(255,255,255);
        $pdf->WriteTag(190,8, utf8_decode('Teste'), 0, 'J');
        $pdf->Ln(10);
                    
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(30,  8, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
        $pdf->Cell(80, 8, utf8_decode('DISCIPLINA'), 1, 0, 'C', true);
        $pdf->Cell(80,  8, utf8_decode('RESPONSÁVEL'), 1, 0, 'C', true);
        $pdf->Ln();      

        $pdf->Output();
    }

    public function edit(Gcub $gcub)
    {
        //
    }

    public function update(Request $request, Gcub $gcub)
    {
        //
    }

    public function destroy(Gcub $gcub)
    {
        //
    }

    public function matricula(\App\Models\Pdf\Matricula $pdf, $codigoGcub)
    {
        //$gcub = Gcub::find($codigoGcub);
        //dd(json_decode($gcub->dadosGcub));

        $pdf->setCabecalho('ppgem');
        $pdf->Output();

    }
}
