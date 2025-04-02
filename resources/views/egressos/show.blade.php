@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-2">
            <img width="200" height="auto" src="/images/logo-ppgem.png">
            <div class="list-group">
                <a href="{{ url('egressos/listar') }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-10">
            <div class="card bg-default">
                <h5 class="card-header">{{ $egresso->egressoNome }} - {{ $egresso->egressoEmail }}</h5>
                
                <div class="card-body">                    
                    <table class="table">                        
                        <thead>
                            <tr>    
                                <th scope="col">Ex-aluno PPGEM?</th>
                                <th scope="col">Nível</th>
                                <th scope="col">Instituição/Empresa e Atividade de Atuação</th>
                                <th scope="col">Atividade relacionada com a formação acadêmica</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ($egresso->egressoRegular == 'S' ? 'Sim' : 'Não') }}</td>
                                <td>{{ $egresso->egressoNivel }}</td>
                                <td>{{ ($egresso->egressoLocal == '' ? '-' : $egresso->egressoLocal) }}</td>
                                <td>{{ $egresso->egressoAtividade }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
