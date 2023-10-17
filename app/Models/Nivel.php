<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class Nivel extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoNivel';
    protected $table      = 'niveis';

    protected $fillable = [
        'codigoNivel',
        'descricaoNivel',
        'siglaNivel',
        'ativoNivel',
        'codigoPessoaAlteracao',
    ];

    public function editais()
    {
        return $this->belongsTo(App\Models\Edital::class);
    }

    public function obterSiglaNivel($codigoNivel)
    {
        $nivel = Nivel::select('siglaNivel')->where('codigoNivel', $codigoNivel)->first(); 


        
        return $nivel->siglaNivel;  
    }
}
