<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;
use Carbon\Carbon;

class Auxiliar extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory;

    protected $primaryKey = 'codigoAuxiliar';
    protected $table      = 'auxiliar';

    protected $fillable = [
        'codigoUsuario',
        'descricaoIdioma',
        'leituraIdioma',
        'redacaoIdioma',
        'conversacaoIdioma'
    ];
}
