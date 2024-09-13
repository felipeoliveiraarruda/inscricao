<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Avaliador extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'avaliadores';
    protected $primaryKey = 'codigoArquivo';

    protected $fillable = [
        'codigoEdital',
        'codigoUsuario',
        'codigoPessoaAlteracao',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function edital()
    {
        return $this->belongsTo(\App\Models\Edital::class);
    }

    public function obterAvaliadores($codigoEdital, $total = false)
    {
        if ($total)
        {
            $avaliadores = Avaliador::where('codigoEdital', $codigoEdital)->count();
        }
        else
        {
            $avaliadores = Avaliador::where('codigoEdital', $codigoEdital)->get();
        }

        
        return $avaliadores;
    }
}