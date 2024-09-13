@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">

                <h5 class="card-header">PAE - Recurso</h5>
                
                <div class="card-body">                                            
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                @if ($msg == 'success')
                                <div class="alert alert-success" id="success-alert">
                                    {{ Session::get('alert-' . $msg) }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @else
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                                @endif
                            @endif
                        @endforeach
                    </div>

                    @if(count($recursos) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nº USP</th>
                                    <th scope="col">Nome</th>                                
                                    <th scope="col">Programa</th>
                                    <th scope="col">Avaliação</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            @foreach($recursos as $recurso)
                                @php
                                    $vinculo = Uspdev\Replicado\Posgraduacao::obterVinculoAtivo($recurso->codpes);
                                @endphp
                                <tr>
                                    <td>{{ $recurso->codpes }}</td>
                                    <td>{{ $recurso->name }}</td>
                                    <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                                    <td>{{ $recurso->notaFinalPae }}</td>
                                    <td>
                                        @if ($recurso->statusRecurso == 'N')
                                            Aberta
                                        @elseif ($recurso->statusRecurso == 'D')
                                            Deferido
                                        @else
                                            Indeferido
                                        @endif
                                    </td>
                                    <td>
                                        <a href="recurso/{{ $recurso->codigoRecurso }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Avaliar Recurso">
                                            <i class="fas fa-book-open"></i>
                                        </a>                                        
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="alert alert-warning">Nenhum recurso cadastrado</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@endsection