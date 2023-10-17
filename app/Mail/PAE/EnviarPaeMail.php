<?php

namespace App\Mail\PAE;

use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarPaeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEdital;

    public function __construct($codigoEdital)
    {
        $this->codigoEdital = $codigoEdital;
    }

    public function build()
    {
        $inscricao   = Inscricao::obterInscricaoPae(Auth::user()->id, $this->codigoEdital);
        $anosemestre = Edital::obterSemestreAno($this->codigoEdital);

        return $this->cc('pae@eel.usp.br')
                    ->replyTo('pae@eel.usp.br')
                    ->subject("[PAE] Processo Seletivo - $anosemestre")
                    ->view('emails.pae.enviar')
                    ->with([
                        'nome'        => Auth::user()->name,
                        'inscricao'   => $inscricao->numeroInscricao,
                        'anosemestre' => $anosemestre
                    ]);
    }
}
