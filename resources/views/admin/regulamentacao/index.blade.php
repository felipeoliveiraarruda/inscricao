@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>

        <div class="col-md-9">    
            <div class="card bg-default">
                <h5 class="card-header">{{ $curso }} - Regulamentação</h5>
                
                <div class="card-body">                
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                            @endif
                        @endforeach
                    </div>                 

                    <table class="table">                        
                        <thead>
                            <tr>
                                <th scope="col">Número USP</th>    
                                <th scope="col">Nome</th>
                                <th scope="col" class="text-center">Status</th> 
                                <th scope="col"></th>
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($inscritos as $inscrito)
                                @php
                                    $status = App\Models\RegulamentosUsers::join('users', 'regulamentos_users.codigoUsuario', '=', 'users.id')
                                                                          ->where('users.codpes', '=', $inscrito['codpes'])
                                                                          ->first();
                                @endphp
                                <tr>
                                    <td>{{ $inscrito['codpes'] }}</td>
                                    <td>{{ $inscrito['nompes'] }}</td>
                                    <td class="text-center">
                                        @if (empty($status))
                                            <i class="fa fa-exclamation-triangle text-warning"></i>
                                        @else
                                            <i class="fa fa-check text-success"></i>
                                        @endif    
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($status->linkArquivo))
                                            <a href="{{ asset('storage/'.$status->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif 
                                    </td>
                                </tr>
                            @endforeach                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection