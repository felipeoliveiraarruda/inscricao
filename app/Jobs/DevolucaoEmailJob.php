<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\DevolucaoMail;

class DevolucaoEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $codigoCurso;
    protected $codigoInscricao;
    protected $email;
    protected $body;
    
    public function __construct($codigoCurso, $email, $codigoInscricao)
    {
        $this->codigoCurso      = $codigoCurso;
        $this->email            = $email;
        $this->codigoInscricao  = $codigoInscricao;
        $this->body             = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::mailer($this->codigoCurso)->to($this->email)->send(new DevolucaoMail($this->codigoInscricao, $this->body));
    }
}
