<?php

namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComprovanteMail extends Mailable
{
    use Queueable, SerializesModels;

   /* protected $codigoInscricao;
    protected $arquivo;

    public function __construct($codigoInscricao, $arquivo)
    {
        $this->codigoInscricao = $codigoInscricao;
        $this->arquivo         = $arquivo;
    }

    public function build()
    {
        $inscricao = Inscricao::find($this->codigoInscricao);
        $edital    = Edital::obterNumeroEdital($inscricao->codigoEdital);

        return $this->from('dev.ci.eel@usp.br')
                    ->subject('Seleção PPGPE 2023')
                    ->view('emails.comprovante')
                    ->attach(storage_path('app/public/'.$this->arquivo))
                    ->with([
                        'nome'      => Auth::user()->name,
                        'inscricao' => $inscricao->numeroInscricao,
                        'edital'    => $edital
                    ]);
    }*/

    protected $codigoInscricao;
 
    public function __construct($codigoInscricao)
    {
        $this->codigoInscricao = $codigoInscricao;
    }

    public function build()
    {
        $inscricao   = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->find($this->codigoInscricao);
        $edital      = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();
        $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital, true);

        return $this->subject("[INSCRIÇÃO EEL/USP] - REQUERIMENTO DE INSCRIÇÃO {$sigla} - ".Str::upper($edital->descricaoNivel)." - {$anosemestre}")
                    ->view('emails.comprovante')
                    ->with([
                        'nome'          => $inscricao->name,
                        'inscricao'     => $inscricao->numeroInscricao,
                        'anosemestre'   => $anosemestre,
                        'edital'        => $edital,
                        'sigla'         => $sigla
                    ]);
    }
}
