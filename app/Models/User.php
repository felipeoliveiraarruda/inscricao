<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use \Spatie\Permission\Traits\HasRoles;
    use \Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'codpes',
        'password',
        'cpf',
        'rg',
        'telefone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function gerarCodigoPessoaExterna()
    {
        $tamanho = 5;
        $prefixo = "88";

        $temp = User::count() + 1;

        $codigo = str_pad($temp, $tamanho, "0", STR_PAD_LEFT);

        return $prefixo.$codigo;
    }

    public function setCpfAttribute($value) 
    {
        $this->attributes['cpf'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function inscricoes()
    {
        return $this->hasMany(\App\Models\Inscricao::class);
    }

    public function arquivos()
    {
        return $this->hasMany(\App\Models\Arquivo::class);
    }

    public function enderecos()
    {
        return $this->hasMany(\App\Models\Endereco::class);
    }

    public function pessoais()
    {
        return $this->hasMany(\App\Models\DadosPessoais::class);
    }

    public function documentos()
    {
        return $this->belongsTo(\App\Models\Documento::class);
    }
}
