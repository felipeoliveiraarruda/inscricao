@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Pessoa a ser notificada em caso de Emergência
                    @if (empty($emergencia->codigoEmergencia))
                        <a href="inscricao/{{ $codigoInscricao }}/emergencia/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
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
                                    @if (!empty($emergencia->codigoEmergencia))
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Telefone</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>                                
                                        <tr>
                                            <td>{{ $emergencia->nomePessoaEmergencia }}</td>
                                            <td>{{ $emergencia->telefonePessoaEmergencia }}</td>
                                            <td>
                                                @if ($status == 'N')
                                                <a href="inscricao/{{ $codigoInscricao }}/emergencia/create" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                    <i class="far fa-edit"></i>
                                                </a>  
                                                @endif                                                
                                            </td>
                                        </tr>
                                    </table>

                                    @if (!empty($emergencia->codigoEmergenciaInscricao))
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Logradrouro</th>
                                                <th scope="col">Cidade/Uf</th>
                                                <th scope="col">CEP</th>
                                            </tr>
                                        </thead>                                
                                        <tr>
                                            <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->bairroEndereco }}</td>
                                            <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                                            <td>{{ $endereco->cepEndereco }}</td>
                                        </tr>
                                    </table>  
                                    @endif                                  
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