<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Emergencia extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEmergencia';
    protected $table      = 'emergencias';
        
    protected $fillable = [
        'codigoUsuario',
        'nomePessoaEmergencia',
        'telefonePessoaEmergencia',
        'codigoPessoaAlteracao',
    ];
}
