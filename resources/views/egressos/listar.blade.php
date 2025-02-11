@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <img width="300" height="auto" src="/images/logo-ppgem.png">
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Egressos</h5>
                
                <div class="card-body">                    
                    <table class="table">                        
                        <thead>
                            <tr>    
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Data Cadastro</th>
                                <th scope="col"></th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($egressos as $egresso)
                                <tr>
                                    <td>{{ $egresso->egressoNome }}</td>
                                    <td>{{ $egresso->egressoEmail }}</td>
                                    <td>{{ $egresso->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="egressos/{{ $egresso->codigoEgresso }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                            <i class="far fa-eye"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $egressos->appends(request()->query())->links() }} 
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
