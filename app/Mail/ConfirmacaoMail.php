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
        $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->find($this->codigoInscricao);
        $edital    = Edital::obterNumeroEdital($inscricao->codigoEdital, true);

        return $this->subject("[INSCRIÇÃO EEL/USP] - CONFIRMAÇÃO DE INSCRIÇÃO {$edital['sigla']} - {$edital['edital']}")
                    ->replyTo('ppgpe@eel.usp.br', 'PPGPE EEL/USP')
                    ->view('emails.confirmacao')
                    ->with([
                        'nome'      => $inscricao->name,
                        'inscricao' => $inscricao->numeroInscricao,
                        'edital'    => $edital['edital'],
                        'sigla'     => $edital['sigla']
                    ]);
    }
}
