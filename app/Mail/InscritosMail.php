<?php

namespace App\Mail;

use App\Models\Edital;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscritosMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEdital; 
    protected $assunto;
    protected $body;
    
    public function __construct($codigoEdital, $assunto, $body)
    {
        $this->codigoEdital = $codigoEdital;
        $this->assunto      = $assunto;
        $this->body         = $body;
    }

    public function build()
    {
        $edital = Edital::obterNumeroEdital($this->codigoEdital, true);

        return $this->subject("[INSCRIÇÃO EEL/USP] - {$this->assunto} - {$edital['sigla']} - {$edital['edital']}")
                    ->replyTo('ppgpe@eel.usp.br', 'PPGPE EEL/USP')
                    ->view('emails.enviar')
                    ->with([
                        'assunto'   => $this->assunto,                     
                        'body'      => $this->body,
                    ]);
    }
}
