<?php

namespace App\Mail\Estagio;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Estagio;

class InscricaoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEstagio; 
    
    public function __construct($codigoEstagio)
    {
        $this->codigoEstagio = $codigoEstagio;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $estagio = Estagio::find($this->codigoEstagio);
        $link    = env('APP_URL')."/estagios/comunicacao/{$this->codigoEstagio}/show";

        return $this->subject("[ESTAGIO COMUNICAÇÃO 2025] - INSCRIÇÃO")
                    ->view('emails.estagios.inscricao')
                    ->with([
                        'nome'   => $estagio->nomeEstagio,
                        'link'   => $link,
                    ]);
    }
}
