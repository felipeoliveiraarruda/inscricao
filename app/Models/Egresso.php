<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Utils;

class Egresso extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEgresso';
    protected $table      = 'egressos';

    protected $fillable = [
        'egressoNome',
        'egressoEmail',
        'egressoRegular',    
        'egressoNivel',    
        'egressoLocal',
        'egressoAtividade',
    ];
}