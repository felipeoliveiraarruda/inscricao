<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Conceito extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'conceito';
    protected $primaryKey = 'codigoConceito';

    protected $fillable = [
        'descricaoConceito',
        'valorConceito',
        'calculoConceito',
        'statusConceito',
        'codigoPessoaAlteracao',
    ];
}  
