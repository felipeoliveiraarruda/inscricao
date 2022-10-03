<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TipoDocumento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoTipoDocumento';

    protected $fillable = [
        'tipoDocumento',
        'codigoPessoaAlteracao'
    ];

    public function arquivos()
    {
        return $this->hasMany(App\Models\Arquivo::class);
    }
}