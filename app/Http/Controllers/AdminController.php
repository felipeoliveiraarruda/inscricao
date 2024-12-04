<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\User;
use App\Models\Inscricao;
use App\Models\InscricoesProficiencia;
use Mail;
use App\Mail\InscritosMail;
use App\Mail\ClassificadosMail;
use App\Mail\EliminadosMail;
use App\Mail\AusentesMail;
use App\Mail\ApresentacaoMail;
use App\Mail\PAE\InscritosPaeMail;

class AdminController extends Controller
{
    public function index()
    {   
        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        if ((session('level') == 'manager'))
        {
            if (Auth::user()->id == 4)
            {
                $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                                 ->where('codigoUsuario', Auth::user()->id)
                                 ->get();
            }
            else
            {
                $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                                 ->where('codigoUsuario', Auth::user()->id)
                                 ->orWhere('codigoUsuario', 4)
                                 ->get();
            }
        }
        else
        {
            if ((session('level') == 'boss'))
            {
                $editais = Edital::listarEditalDeferimento();
            }
            else
            {
                $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->get();
            }
        } 

        return view('admin.index',
        [
            'editais' => $editais,
            'utils'   => new Utils,    
            'docente' => (in_array("Docente", session('vinculos')) || in_array("Docenteusp", session('vinculos')) ? true : false),
            'level'   => session('level'),
            'pae'     => ((Auth::user()->id == 1 || Auth::user()->id == 4 || Auth::user()->id == 26) ? true : false),
        ]);
    }

    public function listar(Request $request, $id)
    {  
        $codigoCurso = '';
        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('codigoEdital', $id)->first();
        $curso   = Utils::obterCurso($editais->codigoCurso);

        if(isset($request->search)) 
        {
            $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                               ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                               ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                               ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                               ->where('editais.codigoEdital', $id)
                               ->where('users.name', 'LIKE', "%{$request->search}%")
                               ->orWhere('users.email', 'LIKE', "%{$request->search}%")
                               ->get();
        } 
        else 
        {
           // \DB::enableQueryLog();

            if (((in_array("Docenteusp", session('vinculos')) == true) || (in_array("Docente", session('vinculos')) == true)) && (session('level') == 'manager'))
            {
                $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae', 'avaliadores.codigoAvaliador'))
                                   ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                   ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                   ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                   ->join('avaliadores_pae', 'pae.codigoPae', '=', 'avaliadores_pae.codigoPae')
                                   ->join('avaliadores', 'avaliadores_pae.codigoAvaliador', '=', 'avaliadores.codigoAvaliador')
                                   ->where('editais.codigoEdital', $id)
                                   ->where('avaliadores.codigoUsuario', Auth::user()->id)
                                   ->where('inscricoes.statusInscricao', 'C')
                                   ->paginate(10);
            }
            else if ((session('level') == 'manager'))
            {
                if ($editais->codigoNivel == 5)
                {
                    $codigoCurso = Utils::obterCodigoCursoPorEmail(Auth::user()->email);

                    if ($codigoCurso == null)
                    {
                        $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                            ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                            ->where('inscricoes.deleted_at', NULL)
                                            ->where('editais.codigoEdital', $id)->paginate(10);
                    }
                    else
                    {
                        $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                            ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                            ->where('editais.codigoEdital', $id)
                                            ->where('inscricoes.deleted_at', NULL)
                                            ->where('pae.codigoCurso', $codigoCurso)->paginate(10);
                    }

                }
                else
                {
            
                    $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*'))
                                        ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->where('editais.codigoEdital', $id)
                                        ->where('inscricoes.deleted_at', NULL)
                                        ->orderBy('users.name')
                                        ->paginate(30);
                }

            }
            else
            {
                $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                   ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                   ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                   ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                   ->where('editais.codigoEdital', $id)
                                   ->where('inscricoes.deleted_at', NULL)
                                   ->orderBy('users.name')
                                   ->paginate(30);
            }

           // dd(\DB::getQueryLog());   
        }
                
        return view('admin.listar',
        [
            'id'        => $id,
            'inscritos' => $inscritos,
            'curso'     => $curso['nomcur'],
            'docente'   => (in_array("Docenteusp", session('vinculos'))),
            'pae'       => (Auth::user()->id == 4  ? true : false),
        ]);
    }

    public function email($id)
    {
        return view('admin.email',
        [
            'id' => $id,            
        ]);  
    }

    public function enviar_email(Request $request)
    {
        $codigoCurso = '';
        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('codigoEdital', $request->codigoEdital)->first();
        $curso   = Utils::obterCurso($editais->codigoCurso);

        if ($editais->codigoNivel == 5)
        {
            $codigoCurso = Utils::obterCodigoCursoPorEmail(Auth::user()->email);

            if ($codigoCurso == null)
            {
                if ($request->tipoDestinatario[0] == 'T')
                {
                    $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                        ->where('editais.codigoEdital', $request->codigoEdital)                                
                                        ->get();
                }
                else 
                {
                    $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                    ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                    ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                    ->where('editais.codigoEdital', $request->codigoEdital)
                                    ->where('inscricoes.statusInscricao', $request->tipoDestinatario[0])
                                    ->get();                   
                }            
            }
            else
            {
                if ($request->tipoDestinatario[0] == 'T')
                {
                    $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                        ->where('editais.codigoEdital', $request->codigoEdital)   
                                        ->where('pae.codigoCurso', $codigoCurso)                             
                                        ->get();
                }
                else 
                {
                    $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                        ->where('editais.codigoEdital', $request->codigoEdital)
                                        ->where('pae.codigoCurso', $codigoCurso)
                                        ->where('inscricoes.statusInscricao', $request->tipoDestinatario[0])
                                        ->get();                   
                }  
            }
        }
        else
        {
            if ($request->tipoDestinatario[0] == 'T')
            {
                $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                    ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                    ->where('editais.codigoEdital', $request->codigoEdital)                                
                                    ->get();
            }
            else 
            {
                $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                ->where('editais.codigoEdital', $request->codigoEdital)
                                ->where('inscricoes.statusInscricao', $request->tipoDestinatario[0])
                                ->get();                   
            }
        }
        
        foreach($inscritos as $inscrito)
        {
            $codigoCurso = Edital::obterCursoEdital($request->codigoEdital);

            if ($request->codigoEdital == 1)
            {                
                Mail::mailer($codigoCurso)->to(mb_strtolower($inscrito->email))->send(new InscritosPaeMail($request->codigoEdital, $request->assunto, $request->body));
            }
            else
            {
                Mail::mailer($codigoCurso)->to(mb_strtolower($inscrito->email))->send(new InscritosMail($request->codigoEdital, $request->assunto, $request->body));
            }
            
            //Mail::to('felipeoa@usp.br')->send(new InscritosMail($request->codigoEdital, $request->assunto, $request->body));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/enviar-email/{$request->codigoEdital}");  
            }    
            else
            {    
                request()->session()->flash('alert-success', "E-mail enviado com sucesso.");
            }
        }
        
        return redirect("admin/enviar-email/{$request->codigoEdital}");
    }

    public function classificados($id)
    {   
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
        ->where('editais.codigoEdital', $id)
        /*->whereIn('inscricoes.numeroInscricao', [
            "ME4","ME6",
            "ME15","ME18",
            "ME20","ME23","ME24","ME26","ME28","ME29",
            "ME030","ME034","ME35","ME37","ME39",
            "ME042","ME43","ME45","ME48",
            "ME52",
            "ME60","ME62","ME69",
            "ME71","ME72","ME73","ME74",
            "ME80","ME82","ME84","ME85","ME86","ME88",
            "ME90","ME93","ME95","ME96","ME97",
            "ME100","ME101","ME103","ME106","ME108","ME109",
            "ME110","ME111","ME113","ME114","ME116","ME118","ME119",
            "ME120","ME122","ME123","ME128","ME129",
            "ME131","ME132","ME133"])*/
            ->whereIn('inscricoes.numeroInscricao', [
                "ME4","ME6",
                "ME15","ME18",
                "ME20","ME23","ME24","ME26","ME28","ME29",
                "ME30","ME34","ME37","ME39",
                "ME42","ME43","ME45","ME48",
                "ME71","ME72","ME73","ME74",
                "ME80","ME82","ME84","ME85","ME86","ME88",
                "ME90","ME95","ME96","ME97",
                "ME100","ME103","ME106","ME108","ME109",
                "ME110","ME111","ME114","ME118","ME119",
                "ME122","ME123","ME129",
                "ME132","ME133"])            
        ->get();
    
        $editais = Edital::where('codigoEdital', $id)->get();
        
        foreach($editais as $edital)
        {
            $curso = Utils::obterCurso($edital->codigoCurso);
        }
        
        return view('admin.classificados',
        [
            'id'           => $id,
            'inscritos'    => $inscritos,
            'curso'        => $curso['nomcur'],
        ]);
    }  
    
    
    public function enviar_email_classificados($id)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
        ->where('editais.codigoEdital', $id)
        ->whereIn('inscricoes.numeroInscricao', [
            "ME4","ME6",
            "ME15","ME18",
            "ME20","ME23","ME24","ME26","ME28","ME29",
            "ME30","ME34","ME37","ME39",
            "ME42","ME43","ME45","ME48",
            "ME71","ME72","ME73","ME74",
            "ME80","ME82","ME84","ME85","ME86","ME88",
            "ME90","ME95","ME96","ME97",
            "ME100","ME103","ME106","ME108","ME109",
            "ME110","ME111","ME114","ME118","ME119",
            "ME122","ME123","ME129",
            "ME132","ME133"])            
        ->get();
        
        foreach($inscritos as $inscrito)
        {
            Mail::mailer($inscrito->codigoCurso)->to(mb_strtolower($inscrito->email))->send(new ClassificadosMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));
            //Mail::to('felipeoa@usp.br')->send(new ClassificadosMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/");  
            }
        }

        request()->session()->flash('alert-success', "E-mail enviado com sucesso.");                
        return redirect("admin/");
    }

    public function enviar_email_eliminados($id)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
        ->where('editais.codigoEdital', $id)
        ->whereIn('inscricoes.numeroInscricao', [
            "ME14","ME35","ME101", "ME120", "ME128", "ME3", "ME25", "ME52", "ME60", "ME113", "ME116", "ME62", "ME131", "ME22"])            
        ->get();
        
        foreach($inscritos as $inscrito)
        {
            Mail::mailer($inscrito->codigoCurso)->to(mb_strtolower($inscrito->email))->send(new EliminadosMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));
            //Mail::to('felipeoa@usp.br')->send(new EliminadosMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/");  
            }
        }

        request()->session()->flash('alert-success', "E-mail enviado com sucesso.");                
        return redirect("admin/");
    }


    public function enviar_email_ausentes($id)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
        ->where('editais.codigoEdital', $id)
        ->whereIn('inscricoes.numeroInscricao', [
            "ME16","ME69","ME93"])            
        ->get();
        
        foreach($inscritos as $inscrito)
        {
            Mail::mailer($inscrito->codigoCurso)->to(mb_strtolower($inscrito->email))->send(new AusentesMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));
            //Mail::to('felipeoa@usp.br')->send(new EliminadosMail($id, "Resultado da primeira fase do Processo Seletivo do PPGPE-Edital 2022-2023 "));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/");  
            }
        }

        request()->session()->flash('alert-success', "E-mail enviado com sucesso.");                
        return redirect("admin/");
    }


    public function enviar_email_apresentacao($id)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
        ->where('editais.codigoEdital', $id)
        ->whereIn('inscricoes.numeroInscricao', [
            "ME4","ME6",
            "ME15","ME18",
            "ME20","ME23","ME24","ME26","ME28","ME29",
            "ME30","ME34","ME37","ME39",
            "ME42","ME43","ME45","ME48",
            "ME71","ME72","ME73","ME74",
            "ME80","ME82","ME84","ME85","ME86","ME88",
            "ME90","ME95","ME96","ME97",
            "ME100","ME103","ME106","ME108","ME109",
            "ME110","ME111","ME114","ME118","ME119",
            "ME122","ME123","ME129",
            "ME132","ME133"])            
        ->get();
        
        foreach($inscritos as $inscrito)
        {
            Mail::mailer($inscrito->codigoCurso)->to(mb_strtolower($inscrito->email))->send(new ApresentacaoMail($id, "Convocação para 2 fase do Processo Seletivo do PPGPE"));
            
            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/");  
            }
        }

        request()->session()->flash('alert-success', "E-mail enviado com sucesso.");                
        return redirect("admin/");
    }

    public function confirmados(Request $request, $id)
    {  
        $codigoCurso = '';
        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('codigoEdital', $id)->first();
        $curso   = Utils::obterCurso($editais->codigoCurso);

        if(isset($request->search)) 
        {
            $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                               ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                               ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                               ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                               ->where('editais.codigoEdital', $id)
                               ->where('inscricoes.statusInscricao', 'C')
                               ->where('users.name', 'LIKE', "%{$request->search}%")
                               ->orWhere('users.email', 'LIKE', "%{$request->search}%")
                               ->get();
        } 
        else 
        {
            if ((in_array("Docenteusp", session('vinculos')) == true) && (session('level') == 'manager'))
            {
                $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae', 'avaliadores.codigoAvaliador'))
                                   ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                   ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                   ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                   ->join('avaliadores_pae', 'pae.codigoPae', '=', 'avaliadores_pae.codigoPae')
                                   ->join('avaliadores', 'avaliadores_pae.codigoAvaliador', '=', 'avaliadores.codigoAvaliador')
                                   ->where('editais.codigoEdital', $id)
                                   ->where('avaliadores.codigoUsuario', Auth::user()->id)
                                   ->where('inscricoes.statusInscricao', 'C')
                                   ->paginate(10);
            }
            else if ((session('level') == 'manager'))
            {
                if ($editais->codigoNivel == 5)
                {
                    $codigoCurso = Utils::obterCodigoCursoPorEmail(Auth::user()->email);

                    if ($codigoCurso == null)
                    {
                        $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                            ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                            ->where('editais.codigoEdital', $id)
                                            ->where('inscricoes.statusInscricao', 'C')
                                            ->paginate(10);
                    }
                    else
                    {
                        $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                            ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao') 
                                            ->where('editais.codigoEdital', $id)
                                            ->where('inscricoes.statusInscricao', 'C')
                                            ->where('pae.codigoCurso', $codigoCurso)->paginate(10);
                    }

                }
                else
                {
            
                    $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*'))
                                        ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->where('editais.codigoEdital', $id)
                                        ->where('inscricoes.statusInscricao', 'C')
                                        ->orderBy('users.name')
                                        ->paginate(30);
                }

            }
            else
            {
                $inscritos = Edital::select(\DB::raw('inscricoes.*, editais.*, users.*, pae.codigoPae'))
                                   ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                   ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                   ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                   ->where('editais.codigoEdital', $id)
                                   ->where('inscricoes.statusInscricao', 'C')
                                   ->orderBy('users.name')
                                   ->paginate(30);
            }
        }
                
        return view('admin.confirmados',
        [
            'id'        => $id,
            'inscritos' => $inscritos,
            'curso'     => $curso['nomcur'],
            'docente'   => (in_array("Docenteusp", session('vinculos'))),
            'pae'       => (Auth::user()->id == 4  ? true : false),
            'codigoEdital' => $editais->codigoEdital,
        ]);
    }

    public function presenca(\App\Models\Comprovante $pdf, $codigoEdital)
    {            
        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $codigoEdital)->first();        
        $sigla        = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre  = Edital::obterSemestreAno($codigoEdital, true, true);
    
        $pdf->setCabecalhoPresenca($sigla);
        $pdf->setPresenca($sigla);

        $titulo = $pdf->setTituloPresenca($edital->siglaNivel, $edital->codigoCurso, $sigla);
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 14);
        $pdf->SetFillColor(190, 190, 190);
        $pdf->Cell(190, 8, utf8_decode($titulo['titulo']), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode($titulo['sub_titulo']), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','B', 12);
        $pdf->Cell(190, 8, utf8_decode("Exame em {$edital->dataExameEdital->format('d/m/Y')} - Ingresso {$anosemestre}"), 0, 0, 'C');
        $pdf->Ln(15);
        
        $pdf->SetFont('Arial','B', 10);
        $pdf->Cell(190, 8, utf8_decode('MESTRADO'), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->Cell(15, 8, utf8_decode('Nº'), 1, 0, 'C', true);
        $pdf->Cell(75, 8, utf8_decode('Nome'), 1, 0, 'C', true);
        $pdf->Cell(40, 8, utf8_decode('Documento'), 1, 0, 'C', true);
        $pdf->Cell(60, 8, utf8_decode('Assinatura'), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);

        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                           ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                           ->join('documentos', 'documentos.codigoUsuario', '=', 'users.id')
                           ->where('editais.codigoEdital', $codigoEdital)
                           ->where('inscricoes.statusInscricao', 'C')
                           ->orderBy('users.name')
                           ->get();

        $total = 0;

        foreach($inscritos as $inscrito)
        {
            $pdf->Cell(15, 8, utf8_decode($inscrito->numeroInscricao), 1, 0, 'C');
            $pdf->Cell(75, 8, utf8_decode($inscrito->name), 1, 0, 'J');

            if (empty($inscrito->numeroRG))
            {
                $pdf->Cell(40, 8, utf8_decode($inscrito->numeroDocumento), 1, 0, 'C');
            }
            else
            {
                $pdf->Cell(40, 8, utf8_decode($inscrito->numeroRG), 1, 0, 'C');
            }

            $pdf->Cell(60, 8, utf8_decode(''), 1, 0, 'C');
            $pdf->Ln();

            $total++;
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Cell(30, 8, utf8_decode(" Total: {$total}"), 1, 0, 'L');

        $pdf->SetFont('Arial','', 10);
        $pdf->Cell(30, 8, utf8_decode(' Presentes:'), 1, 0, 'L');
        $pdf->Cell(30, 8, utf8_decode(' Ausentes:'), 1, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode(' Aplicador:'), 1, 0, 'L');
        $pdf->Ln(15);

        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $codigoEdital + 1)->first();
        $sigla        = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre  = Edital::obterSemestreAno($codigoEdital, true, true);
       
        $pdf->SetFont('Arial','B', 10);

        $pdf->Cell(190, 8, utf8_decode('DOUTORADO DIRETO'), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->Cell(15, 8, utf8_decode('Nº'), 1, 0, 'C', true);
        $pdf->Cell(75, 8, utf8_decode('Nome'), 1, 0, 'C', true);
        $pdf->Cell(40, 8, utf8_decode('Documento'), 1, 0, 'C', true);
        $pdf->Cell(60, 8, utf8_decode('Assinatura'), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);

        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                           ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                           ->join('documentos', 'documentos.codigoUsuario', '=', 'users.id')
                           ->where('editais.codigoEdital', $codigoEdital + 1)
                           ->where('inscricoes.statusInscricao', 'C')
                           ->orderBy('users.name')
                           ->get();

        $total = 0;

        foreach($inscritos as $inscrito)
        {
            $pdf->Cell(15, 8, utf8_decode($inscrito->numeroInscricao), 1, 0, 'C');
            $pdf->Cell(75, 8, utf8_decode($inscrito->name), 1, 0, 'J');
            
            if (empty($inscrito->numeroRG))
            {
                $pdf->Cell(40, 8, utf8_decode($inscrito->numeroDocumento), 1, 0, 'C');
            }
            else
            {
                $pdf->Cell(40, 8, utf8_decode($inscrito->numeroRG), 1, 0, 'C');
            }

            $pdf->Cell(60, 8, utf8_decode(''), 1, 0, 'C');
            $pdf->Ln();

            $total++;
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Cell(30, 8, utf8_decode(" Total: {$total}"), 1, 0, 'L');

        $pdf->SetFont('Arial','', 10);
        $pdf->Cell(30, 8, utf8_decode(' Presentes:'), 1, 0, 'L');
        $pdf->Cell(30, 8, utf8_decode(' Ausentes:'), 1, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode(' Aplicador:'), 1, 0, 'L');
        $pdf->Ln(25);

        
        $pdf->SetFont('Arial','B', 12);
        $pdf->Cell(190, 8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('Coordenador da CCP-PPGEM'), 0, 0, 'C');

        $pdf->Output('I', 'presenca.pdf');
    }

    public function lista_disciplina($codigoEdital)
    {
        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        $inscritos = Inscricao::select(\DB::raw('inscricoes_disciplinas.codigoDisciplina, COUNT(inscricoes_disciplinas.codigoDisciplina) AS total'))
                              ->join('inscricoes_disciplinas', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                              ->where('inscricoes.codigoEdital', $codigoEdital)
                              //->where('inscricoes.statusInscricao', 'C')
                              ->groupBy('inscricoes_disciplinas.codigoDisciplina')
                              ->orderBy('inscricoes_disciplinas.codigoDisciplina')
                              ->get();

        return view('admin.especial.disciplinas',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $inscritos,
            'level'   => session('level'),
        ]);
    }

    public function lista_ppgpe($codigoEdital)
    {
        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                            ->join('inscricoes_documentos', 'inscricoes.codigoInscricao', '=', 'inscricoes_documentos.codigoInscricao')
                            ->join('documentos', 'inscricoes_documentos.codigoDocumento', '=', 'documentos.codigoDocumento')
                            ->where('editais.codigoEdital', $codigoEdital)
                            ->where('inscricoes.statusInscricao', 'C')
                            ->orderBy('users.name')
                            ->get();    

        return view('admin.ppgpe.lista',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $inscritos,
            'level'   => session('level'),
        ]);
    }

    public function nomes($codigoEdital)
    {
        \App\Models\User::acertarNomes($codigoEdital);
    } 

    public function exame($codigoEdital)
    {
        $editais = Edital::find($codigoEdital);
        $curso   = Utils::obterCurso($editais->codigoCurso);

        $inscritos = Inscricao::join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                              ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                              ->where('inscricoes.codigoEdital', $codigoEdital)
                              ->where('inscricoes.statusInscricao', 'C')
                              ->orderBy('users.name')
                              ->get();

        $aprovados = Inscricao::join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                              ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                              ->where('inscricoes.codigoEdital', $codigoEdital)
                              ->where('inscricoes_proficiencia.statusProficiencia', 'S')
                              ->count();                              

        return view('admin.exame.index',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $inscritos,
            'curso'         => $curso['nomcur'],
            'aprovados'     => $aprovados,
        ]);                              
    } 

    public function exame_create($codigoEdital)
    {
        $editais = Edital::find($codigoEdital);
        $curso   = Utils::obterCurso($editais->codigoCurso);

        $inscritos = Inscricao::join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                              ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                              ->where('inscricoes.codigoEdital', $codigoEdital)
                              ->where('inscricoes.statusInscricao', 'C')
                              ->orderBy('users.name')
                              ->get();

        return view('admin.exame.create',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $inscritos,
            'curso'         => $curso['nomcur'],
        ]);                              
    } 

    public function exame_store(Request $request)
    {
        foreach($request->inlineResultado as $key => $value) 
        {
            $proficiencia = InscricoesProficiencia::find($key);
            $proficiencia->statusProficiencia = $value;
            $proficiencia->save();
        }

        request()->session()->flash('alert-success', 'Resultado do Exame de Proficiência cadastrado com sucesso.');
        
        return redirect(url("admin/{$request->codigoEdital}/exame"));        
    }
}