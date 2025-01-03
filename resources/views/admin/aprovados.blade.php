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
                <h5 class="card-header">{{ $curso }} - Lista de Aprovados</h5>
                
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
                                <th scope="col">Primeira Matrícula</th> 
                                <th scope="col"></th> 
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($inscritos as $inscrito)        
                                @php
                                    $aprovado = App\Models\ProcessoSeletivo::obterAprovado($inscrito->codigoUsuario);                                    
                                    $seletivo = App\Models\ProcessoSeletivo::obterInscricaoAprovado($aprovado);
                                @endphp                            
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>{{ $inscrito->email }}</td>
                                    <td>
                                        <i class="fa @if (!empty($seletivo->codigoInscricaoDisciplina)) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
                                    </td>
                                </tr>
                            @endforeach                        
                        </tbody>
                    </table>

                    @empty(request()->search)
                        {{ $inscritos->appends(request()->query())->links() }} 
                    @endempty
                </div>
            </div>
        </div>
    </div>
</main>

@endsection