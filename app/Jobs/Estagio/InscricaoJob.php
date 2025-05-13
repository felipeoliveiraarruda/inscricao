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

class InscricaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $codigoEstagio; 

    public function __construct($codigoEstagio)
    {
        $this->codigoEstagio = $codigoEstagio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('cpq@eel.usp.br')->send(new InscricaoMail($this->codigoEstagio));
    }
}
