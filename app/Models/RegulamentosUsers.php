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

class RegulamentosUsers extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoRegulamentoUser';
    protected $table      = 'regulamentos_users';
    
    protected $fillable = [
        'codigoRegulamento',
        'codigoUsuario',
        'statusRegulamento',
        'codigoPessoaAlteracao',
    ];
        
    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
    }

    public function regulamentos()
    {
        return $this->belongsTo(App\Models\Regulamento::class);
    }
}
