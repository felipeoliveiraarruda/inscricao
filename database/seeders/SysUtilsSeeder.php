<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SysUtilsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sys_utils')->insert(
            [
                [
                    'dadosSexo'                 => 'Masculino|Feminino',
                    'dadosRaca'                 => 'Não dispõe da informação|Branca|Negra|Parda|Amarela|Indígena|Não declarado',
                    'dadosEstadoCivil'          => 'Casado(a)|Solteiro(a)|Solteiro(a)|Viúvo(a)|Separado(a) Judicialmente|Disquitado(a)|Divorciado|União Estável|União Homoafetiva',
                    'dadosNecessidadeEspecial'  => 'Auditiva|Motora|Reabilitada|Visual',
                    'dadosIdioma'               => 'Pouco|Razoavelmente|Bem',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),                    
                    'codigoPessoaAlteracao'     => 5840128,
                ],
            ],                                                    
        );
    }
}
