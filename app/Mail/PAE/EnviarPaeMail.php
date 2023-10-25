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
    protected $codigoUsuario;

    public function __construct($codigoEdital, $codigoUsuario)
    {
        $this->codigoEdital = $codigoEdital;
        $this->codigoUsuario = $codigoUsuario;
    }

    public function build()
    {
        $inscricao   = Inscricao::obterInscricaoPae($this->codigoUsuario, $this->codigoEdital);
        $anosemestre = Edital::obterSemestreAno($this->codigoEdital);

        return $this->replyTo('pae@eel.usp.br')
                    ->subject("[PAE] Processo Seletivo - $anosemestre")
                    ->view('emails.pae.enviar')
                    ->with([
                        'nome'         => Auth::user()->name,
                        'inscricao'    => $inscricao->numeroInscricao,
                        'anosemestre'  => $anosemestre,
                        'codigoEdital' => $this->codigoEdital,
                    ]);
    }
}
