<?php

namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacaoMail extends Mailable
{
    use Queueable, SerializesModels;

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


        return $this->subject("[INSCRIÇÃO EEL/USP] - CONFIRMAÇÃO DE INSCRIÇÃO {$sigla} - {$anosemestre}")
                    ->replyTo($edital->email, "{$sigla} EEL/USP")
                    ->view('emails.confirmacao')
                    ->with([
                        'nome'      => $inscricao->name,
                        'inscricao' => $inscricao->numeroInscricao,
                        'edital'    => $anosemestre,
                        'sigla'     => $sigla
                    ]);
    }
}
