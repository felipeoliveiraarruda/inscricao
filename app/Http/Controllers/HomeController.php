<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\User;
use App\Models\TipoDocumento;
use App\Models\ProcessoSeletivo;
use App\Models\Regulamento;
use Carbon\Carbon;
use Mail;
use App\Mail\ConfirmacaoMail;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::guest())
        {
            $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                             ->where('dataFinalEdital', '>=',  Carbon::now())->get();

            $encerrados = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')            
                                ->where('dataFinalEdital', '<',  Carbon::now())->paginate(5);     
                                
            $regulamentos = Regulamento::where('dataFinalRegulamento', '>=',  Carbon::now())->get();
            
            return view('index', 
            [
                'editais'       => $editais,
                'encerrados'    => $encerrados,
                'regulamentos'  => $regulamentos,
                'utils'         => new Utils,        
            ]);
        }
        else
        {
            if (empty(session('level')))
            {
                Utils::setSession(Auth::user()->id);
            }
            
            if ((session('level') == 'admin' || session('level') == 'manager'))
            {
                return redirect('admin');                
            }

            if ((in_array("Docenteusp", session('vinculos')) == true) || (in_array("Docente", session('vinculos')) == true))
            {
                if (session('level') == 'manager')
                {
                    return redirect('admin'); 
                }

                if (session('level') == 'boss')
                {
                    return redirect('admin'); 
                }      
            }
            else
            {
                $aprovado = ProcessoSeletivo::obterAprovado();

                if (!empty($aprovado))
                {
                    $seletivo = ProcessoSeletivo::obterInscricaoAprovado($aprovado);

                    if (empty($seletivo->codigoInscricaoDisciplina))
                    {
                        return redirect("inscricao/{$seletivo->codigoInscricao}/matricula");
                    }
                    else
                    {
                        return redirect('dashboard');
                    }
                }
                else
                {
                    return redirect('dashboard');
                }
            }
        }
    }

    public function verificacao($cpf)
    {
        $user = User::select('cpf')->where('cpf', '=', preg_replace('/[^0-9]/', '', $cpf))->count();
        return json_encode($user);
    }
}
