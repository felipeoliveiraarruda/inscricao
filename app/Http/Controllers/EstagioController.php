<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EstagioVerificacao;
use App\Models\Estagio;
use App\Models\Utils;
use App\Models\Auxiliar;
use App\Models\EditalEstagio;
use App\Http\Requests\EstagioPostRequest;
use Carbon\Carbon;
use Mail;
use App\Jobs\Estagio\ConfirmacaoJob;
use App\Jobs\Estagio\InscricaoJob;


class EstagioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo = '')
    {    
        if ($tipo == '')        
        {
            $editais = EditalEstagio::whereRaw('NOW() > `dataInicioEditalEstagio` AND NOW() < `dataFinalEditalEstagio`')->get();
            
            return view('estagios.index',
            [
                'tipo'      => $tipo,
                'editais'   => $editais
            ]); 
        }
        else
        {
            $editais = EditalEstagio::whereRaw('NOW() > `dataInicioEditalEstagio` AND NOW() < `dataFinalEditalEstagio`')->count();

            if($editais == 0)
            {
                $item = array();
                $item['title'] = 'Aviso';
                $item['story'] = 'Inscrições Encerradas';
    
                return view('components.modal',
                [
                    'item' => $item,                
                ]);
            }
            else
            {
                return view("estagios.{$tipo}.index",
                [
                    'tipo'  => $tipo,
                ]); 
            }            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($tipo = '')
    {
        $editais = EditalEstagio::whereRaw('NOW() > `dataInicioEditalEstagio` AND NOW() < `dataFinalEditalEstagio`')->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscrições Encerradas';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }

        return view("estagios.comunicacao.create",
        [
            'tipo'          => $tipo,
            'cpfEstagio'    => session('cpfEstagio'),
            'idiomas'       => Utils::obterDadosSysUtils('idioma'),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstagioPostRequest $request)
    {        
        $validated = $request->validated();

        $idiomas = array();

        $pathCurriculo = $request->file('curriculoEstagio')->store('estagios/comunicacao', 'public');
        $pathTrabalho = $request->file('trabalhoEstagio')->store('estagios/comunicacao', 'public');

        $auxiliar = Auxiliar::where('codigoUsuario', session('cpfEstagio'))->get();

        if (!empty($auxiliar))
        {
            foreach($auxiliar as $idioma)
            {
                array_push($idiomas, [
                    'descricaoIdioma' => $idioma->descricaoIdioma,
                    'leituraIdioma' => $idioma->leituraIdioma,
                    'redacaoIdioma' => $idioma->redacaoIdioma,
                    'conversacaoIdioma' => $idioma->conversacaoIdioma,
                ]);
            }
        }

        $estagio = Estagio::create([        
            "codigoEditalEstagio" => 1,
            "cpfEstagio" => $request->cpfEstagio,
            "nomeEstagio" => $request->nomeEstagio,
            "emailEstagio" => $request->emailEstagio,
            "telefoneEstagio" => $request->telefoneEstagio,
            "cepEnderecoEstagio" => $request->cep,
            "logradouroEnderecoEstagio" => $request->logradouro,
            "numeroEnderecoEstagio" => $request->numero,
            "complementoEnderecoEstagio" => $request->complemento,
            "bairroEnderecoEstagio" => $request->bairro,
            "localidadeEnderecoEstagio" => $request->localidade,
            "ufEnderecoEstagio" => $request->uf,
            "cursoEstagio" => $request->cursoEstagio,
            "semestreEstagio" => $request->semestreEstagio,
            "facebookEstagio" => $request->facebookEstagio,
            "instagramEstagio" => $request->instagramEstagio,
            "twitterEstagio" => $request->twitterEstagio,
            "wordEstagio" => $request->wordEstagio,
            "excelEstagio" => $request->excelEstagio,
            "powerPointEstagio" => $request->powerPointEstagio,
            "podcastEstagio" => $request->podcastEstagio,
            "doodleEstagio" => $request->doodleEstagio,
            "facebookTextEstagio" => $request->facebookTextEstagio,
            "instagramTextEstagio" => $request->instagramTextEstagio,
            "twitterTextEstagio" => $request->twitterTextEstagio,
            "linkedinTextEstagio" => $request->linkedinTextEstagio,
            "idiomasEstagio" => (empty($idiomas) ? NULL : json_encode($idiomas)),
            "curriculoEstagio" => $pathCurriculo,
            "trabalhoEstagio" => $pathTrabalho 
        ]);

        Auxiliar::where('codigoUsuario', session('cpfEstagio'))->delete();

        $request->session()->forget('cpfEstagio');

        dispatch(new ConfirmacaoJob($codigoEstagio, $estagio->emailEstagio));
        dispatch(new InscricaoJob($estagio->codigoEstagio));

        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro no envio do e-mail de inscrição.');
        }    
        else
        {    
            request()->session()->flash('alert-success','Inscrição realizada com sucesso.'); 
        }
        
        return redirect("estagios/comunicacao");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estagio  $estagio
     * @return \Illuminate\Http\Response
     */
    public function show($tipo = '', $codigoEstagio)
    {
        $estagio = Estagio::find($codigoEstagio);
        
        return view('estagios.comunicacao.show',
        [            
            'estagio' => $estagio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estagio  $estagio
     * @return \Illuminate\Http\Response
     */
    public function edit(Estagio $estagio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estagio  $estagio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estagio $estagio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estagio  $estagio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estagio $estagio)
    {
        //
    }

    public function verificacao(EstagioVerificacao $request)
    {
        $validated = $request->validated();

        $estagio = Estagio::where('cpfEstagio', $request->cpfPessoal)->first();
        session(['cpfEstagio' => $request->cpfPessoal]);

        if ($estagio == '')
        {
            return redirect("estagios/comunicacao/create");
        }
    }

    public function idioma_store(Request $request)
    {
        $idiomas = $request->input('idiomas');
        $total   = count($idiomas);
        $i = 0;
        $columns = '';
        $values  = '';
        $virgula = '';

        foreach($idiomas as $key => $value)
        {
            if ($i < $total - 1)
            {
                $virgula = ', ';
            }

            $columns .= "{$key}{$virgula}";
            $values  .= "'".addslashes($value)."'{$virgula}";

            $virgula = '';
            $i++;
        }

        \DB::insert("insert into auxiliar (codigoUsuario, {$columns}) values ('".session('cpfEstagio')."', {$values})");

        $auxiliar = Auxiliar::where('codigoUsuario', session('cpfEstagio'))->get();

        return view('estagios.comunicacao.idiomas',
        [            
            'idiomas' => $auxiliar,
        ]);
    }

    public function listar($tipo = '')
    {
        if ($tipo == 'comunicacao')
        {
            $estagios = Estagio::where('codigoEditalEstagio', 1)->get();
        }
        
        return view('estagios.comunicacao.listar',
        [            
            'estagios' => $estagios,
        ]);
    }
}
