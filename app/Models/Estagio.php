<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Estagio extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEstagio';
    protected $table      = 'estagios';
        
    protected $fillable = [
        'codigoEditalEstagio',
        'cpfEstagio',
        'nomeEstagio',
        'emailEstagio',
        'telefoneEstagio',
        'cursoEstagio',
        'semestreEstagio',
        'cepEnderecoEstagio',
        'logradouroEnderecoEstagio',
        'numeroEnderecoEstagio',
        'complementoEnderecoEstagio',
        'bairroEnderecoEstagio',
        'localidadeEnderecoEstagio',
        'ufEnderecoEstagio',
        'facebookEstagio',
        'instagramEstagio',
        'twitterEstagio',
        'wordEstagio',
        'excelEstagio',
        'powerPointEstagio',
        'podcastEstagio',
        'doodleEstagio',
        'facebookTextEstagio',
        'instagramTextEstagio',
        'twitterTextEstagio',
        'linkedinTextEstagio',
        'idiomasEstagio',
        'curriculoEstagio',
        'trabalhoEstagio',
    ];

    public function editais_estagios()
    {
        return $this->hasMany(\App\Models\EditalEstagio::class);
    }

    public function setCpfEstagioAttribute($value) 
    {
        $this->attributes['cpfEstagio'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setNomeEstagioAttribute($value) 
    {
        $this->attributes['nomeEstagio'] = Utils::tratarNome($value);
    }
}
