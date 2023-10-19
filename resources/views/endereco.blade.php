@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Endereço
                    @if (empty($enderecos->codigoEndereco))
                        <a href="inscricao/{{ $codigoInscricao }}/endereco/create/" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right">Novo</a>
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
                                    @if (!empty($enderecos->codigoEndereco))
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Logradrouro</th>
                                                <th scope="col">Cidade/Uf</th>
                                                <th scope="col">CEP</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                    
    
                                        @foreach ($enderecos as $endereco)
                                        <tr>
                                            <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->bairroEndereco }}</td>
                                            <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                                            <td>{{ $endereco->cepEndereco }}</td>
                                            <td>
                                                <a href="endereco/editar" role="button" aria-pressed="true" class="btn btn-warning btn-sm" target="_new" title="Editar">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                <a href="inscricao/endereco/remover/{{ $codigoInscricao }}/{{ $endereco->codigoEndereco }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" title="Remover">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </table>
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