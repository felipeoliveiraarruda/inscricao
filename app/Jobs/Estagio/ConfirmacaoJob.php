<?php

namespace App\Jobs\Estagio;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\Estagio\InscricaoMail;

class ConfirmacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $codigoEstagio; 
    protected $emailEstagio;

    public function __construct($codigoEstagio, $emailEstagio)
    {
        $this->codigoEstagio = $codigoEstagio;
        $this->emailEstagio  = $emailEstagio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::mailer('cpg')->to($this->emailEstagio)->send(new InscricaoMail($this->codigoEstagio));
    }
}
