<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;
use Carbon\Carbon;

class EditalEstagio extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEditalEstagio';
    protected $table      = 'editais_estagios';
    protected $dates      = ['dataInicioEditalEstagio', 'dataFinalEditalEstagio'];
    
    protected $fillable = [
        'descricaoEditalEstagio',
        'linkEditalEstagio',
        'dataInicioEditalEstagio',
        'dataFinalEditalEstagio',
    ];

    protected $casts = [
        'dataInicioEditalEstagio' => 'datetime',
        'dataFinalEditalEstagio' => 'datetime',
    ];
}
