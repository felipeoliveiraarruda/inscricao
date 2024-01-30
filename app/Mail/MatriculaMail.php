<?php

namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatriculaMail extends Mailable
{
    use Queueable, SerializesModels;
        
    protected $codigoInscricao;
    protected $anexo;
 
    public function __construct($codigoInscricao, $anexo)
    {
        $this->codigoInscricao = $codigoInscricao;
        $this->anexo           = $anexo;
    }


    public function build()
    {
        $inscricao   = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->find($this->codigoInscricao);
        $edital      = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();
        $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital, true);

        return $this->subject("[INSCRIÇÃO EEL/USP] - REQUERIMENTO PRIMEIRA MATRÍCULA {$sigla} - ".Str::upper($edital->descricaoNivel)." - {$anosemestre}")
                    ->replyTo('ppgem-eel@usp.br', 'PPGEM EEL/USP')                    
                    ->attach($this->anexo)
                    ->view('emails.matricula')
                    ->with([
                        'nome'          => $inscricao->name,
                        'inscricao'     => $inscricao->numeroInscricao,
                        'edital'        => $anosemestre,
                    ]);
    }
}
