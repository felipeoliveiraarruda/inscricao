@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Classificação {{ $anosemestre }}</h5>
                
                <div class="card-body">                                            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Nome</th>                                
                                <th scope="col">Classificação</th>
                            </tr>
                        </thead>
                        @foreach($inscritos as $inscrito)
                        <tr>
                            <td>{{ $inscrito->codpes }}</td>
                            <td>{{ $inscrito->name }}</td>
                            <td>{{ $inscrito->classificacaoPae }}</td>
                            <td>{{ $inscrito->notaFinalPae }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection