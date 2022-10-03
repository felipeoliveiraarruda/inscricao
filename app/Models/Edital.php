<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Edital extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoEdital';
    protected $table      = 'editais';
    protected $dates      = ['dataInicioEdital', 'dataFinalEdital, dataDoeEdital'];
    
    protected $fillable = [
        'codigoCurso',
        'nivelEdital',
        'linkEdital',
        'dataInicioEdital',
        'dataFinalEdital',
        'dataDoeEdital',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'dataInicioEdital' => 'datetime',
        'dataFinalEdital' => 'datetime',
        'dataDoeEdital' => 'datetime',
     ];

    public function inscricoes()
    {
        return $this->hasMany(\App\Models\Inscricao::class);
    }

    public function obterNumeroEdital($codigoEdital)
    {
        $edital = Edital::select('dataInicioEdital')
                        ->where('codigoEdital', $codigoEdital)
                        ->first();

        $ano      = $edital->dataInicioEdital->format('Y');
        $semestre = ($edital->dataInicioEdital->format('m') < 7 ? "01" : "02");
        
        return "{$semestre}/{$ano}";
    }

    public function obterNivelEdital($codigoEdital)
    {
        $nivel = Edital::select('nivelEdital')->where('codigoEdital', $codigoEdital)->first();        
        return $nivel->nivelEdital;
    }
}
