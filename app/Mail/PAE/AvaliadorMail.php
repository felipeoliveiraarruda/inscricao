<?php

namespace App\Mail\PAE;

use App\Models\Edital;
use App\Models\PAE\Pae;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvaliadorMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEdital;
    protected $total; 

    public function __construct($codigoEdital)
    {
        $this->codigoEdital = $codigoEdital;
        $this->total        = 0;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $anosemestre = Edital::obterSemestreAno($this->codigoEdital);
        $this->total = Pae::obterConfirmados($this->codigoEdital, true);

        return $this->replyTo('pae@eel.usp.br')
                    ->subject("[PAE] Processo seletivo do PAE/EEL {$anosemestre} - Análise de documentação comprobatória")
                    ->view('emails.pae.avaliador')
                    ->with([
                        'total' => $this->total,
                    ]);
    }
}
