@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                <div class="card-body">
                    <a href="admin/" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>
                </div>
            </div>         
        </div>

        <div class="col-md-9">    
            <div class="card bg-default">
                <h5 class="card-header">{{ $curso }} - Classificados</h5>
                
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
                                <th scope="col">Inscrição</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col"></th> 
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($inscritos as $inscrito)
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>{{ $inscrito->email }}</td>
                                    <td>
                                        <a href="inscricao/visualizar/{{ $inscrito->codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning">Visualizar</a>
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