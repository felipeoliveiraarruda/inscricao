<?php

namespace App\Mail;

use App\Models\Edital;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassificadosMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEdital; 
    protected $assunto;

    public function __construct($codigoEdital, $assunto)
    {
        $this->codigoEdital = $codigoEdital;
        $this->assunto      = $assunto;
    }

    public function build()
    {
        $edital = Edital::obterNumeroEdital($this->codigoEdital, true);

        return $this->subject("{$this->assunto} - {$edital['sigla']} - {$edital['edital']}")
                    ->replyTo('ppgpe@eel.usp.br', 'PPGPE EEL/USP')
                    ->view('emails.classificado')
                    ->with([
                        'assunto'   => $this->assunto,
                    ]);
    }
}
