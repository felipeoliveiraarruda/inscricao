@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Conhecimento de Idiomas Estrangeiros
                    @if ($status == 'N')
                    <a href="inscricao/{{ $codigoInscricao }}/idioma/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>  
                    </a>
                    @endif
                </h5>

                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <div class="col-md-12">
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
                            
                            <div class="row">                             
                                <div class="col-sm-12"> 
                                    @if (!empty($idiomas[0]->codigoIdioma))                                    
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Idioma</th>
                                                <th scope="col">Leitura</th>
                                                <th scope="col">Redação</th>
                                                <th scope="col">Conversação</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        @foreach($idiomas as $idioma)                                
                                        <tr>
                                            <td>{{ $idioma->descricaoIdioma }}</td>
                                            <td>{{ $idioma->leituraIdioma }}</td>
                                            <td>{{ $idioma->redacaoIdioma }}</td>
                                            <td>{{ $idioma->conversacaoIdioma }}</td>
                                            <td>
                                                @if ($status == 'N')
                                                <a href="inscricao/{{ $codigoInscricao }}/idioma/create/{{ $idioma->codigoIdioma }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                @endif 
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>                                  
                                    @endif                                    
                                </div>                                 
                            </div>                                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection