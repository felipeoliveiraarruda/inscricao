@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ url('admin') }}" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>

        <div class="col-md-9">    
            <div class="card bg-default">
                <h5 class="card-header">{{ $curso }} - Exame de Proficiência
                    @if ($aprovados > 0)
                        <a href="imprimir/certificado-proficiencia/{{ $codigoEdital }}/" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right ml-1" data-toggle="tooltip" data-placement="bottom" title="Certificados">
                            <i class="fa fa-print"></i>  
                        </a>
                    @endif

                    <a href="admin/{{ $codigoEdital }}/exame/create" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5>
                
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

                    <table class="table">                        
                        <thead>
                            <tr>    
                                <th scope="col">Inscrição</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Resultado</th> 
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($inscritos as $inscrito)                     
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>{{ $inscrito->email }}</td>
                                    @if ($inscrito->statusProficiencia == 'R')
                                        <th scope="col" class="text-danger">Reprovado</th>
                                    @elseif ($inscrito->statusProficiencia == 'S')
                                        <th scope="col" class="text-success">Aprovado</th>
                                    @elseif ($inscrito->statusProficiencia == 'A')
                                        <th scope="col" class="text-warning">Ausente</th>
                                    @else
                                        <th scope="col">-</th>
                                    @endif
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