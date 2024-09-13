@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @if (Session('level') == 'user')
                @include('inscricao.menu')
            @else
                @include('inscricao.visualizar.admin.menu')
            @endif
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Resumo Escolar @if(Session::get('level') == 'manager') - {{ Session::get('total')['inscricao'] }} @endif
                    @if(Session::get('level') == 'user')
                        @if ($status == 'N' && (empty($escolares[0]->codigoResumoEscolar)))
                        <a href="inscricao/{{ $codigoInscricao }}/escolar/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                            <i class="fa fa-plus"></i>                        
                        </a>
                        @endif
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
                                    @if (!empty($escolares[0]->codigoResumoEscolar))
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Escola</th>
                                                <th scope="col">Título/Especialidade</th>
                                                <th scope="col">Ano Titulação</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        @foreach($escolares as $escolar)                                
                                        <tr>
                                            <td>{{ $escolar->escolaResumoEscolar }}</td>
                                            <td>{{ $escolar->especialidadeResumoEscolar }}</td>
                                            <td>{{ ($escolar->finalResumoEscolar == '' ? '' : $escolar->finalResumoEscolar->format('Y')) }}</td>
                                            <td>
                                                @if(Session::get('level') == 'user')
                                                    @if ($status == 'N')  
                                                        <a href="inscricao/{{ $codigoInscricao }}/escolar/create/{{ $escolar->codigoResumoEscolar }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    @endif
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