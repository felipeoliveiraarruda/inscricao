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

class ComprovanteMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $codigoInscricao;
    protected $arquivo;

    public function __construct($codigoInscricao, $arquivo)
    {
        $this->codigoInscricao = $codigoInscricao;
        $this->arquivo         = $arquivo;
    }

    public function build()
    {
        $inscricao = Inscricao::find($this->codigoInscricao);
        $edital    = Edital::obterNumeroEdital($inscricao->codigoEdital);

        return $this->from('dev.ci.eel@usp.br')
                    ->subject('Seleção PPGPE 2023')
                    ->view('emails.comprovante')
                    ->attach(storage_path('app/public/'.$this->arquivo))
                    ->with([
                        'nome'      => Auth::user()->name,
                        'inscricao' => $inscricao->numeroInscricao,
                        'edital'    => $edital
                    ]);
    }
}
