<?php

namespace App\Mail\Especial;

use App\Models\Edital;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SituacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoEdital; 

    public function __construct()
    {
        $this->codigoEdital = $codigoEdital;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $edital = Edital::obterNumeroEdital($this->codigoEdital, true);

        
        return $this->view('view.name');
    }
}
