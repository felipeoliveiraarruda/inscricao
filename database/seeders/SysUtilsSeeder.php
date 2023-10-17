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
                    'dadosRaca'                 => 'N�o disp�e da informa��o|Branca|Negra|Parda|Amarela|Ind�gena|N�o declarado',
                    'dadosEstadoCivil'          => 'Casado(a)|Solteiro(a)|Solteiro(a)|Vi�vo(a)|Separado(a) Judicialmente|Disquitado(a)|Divorciado|Uni�o Est�vel|Unio Homoafetiva',
                    'dadosNecessidadeEspecial'  => 'Auditiva|Motora|Reabilitada|Visual',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),                    
                    'codigoPessoaAlteracao'     => 5840128,
                ],
            ],                                                    
        );
    }
}
