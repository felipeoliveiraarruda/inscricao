<?php

namespace App\Mail\PAE;

use App\Models\Edital;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscritosPaeMail extends Mailable
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
        $anosemestre = Edital::obterSemestreAno($this->codigoEdital);

        return $this->replyTo('pae@eel.usp.br')
                    ->subject("[PAE] Processo Seletivo - $anosemestre")
                    ->view('emails.enviar')
                    ->with([
                        'assunto'   => $this->assunto,                     
                        'body'      => $this->body,
                    ]);
    }
}
