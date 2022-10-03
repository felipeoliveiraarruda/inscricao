@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Endereço</h5>
                
                <div class="card-body">                    
                    <div class="row justify-content-center">
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
                        <div class="col-md-12 text-right">
                            <a href="/endereco/novo" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a>
                            <a href="/dashboard" role="button" aria-pressed="true" class="btn btn-primary btn-sm">Voltar</a>
                        </div>
                                                        
                        <div class="col-md-12">                                    
                            <p></p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>    
                                        <th scope="col">Logradrouro</th>
                                        <th scope="col">Cidade/Uf</th>
                                        <th scope="col">CEP</th>
                                    </tr>
                                </thead>
                                @foreach ($enderecos as $endereco)
                                <tr>
                                    <td>{{ $endereco->codigoEndereco }}</td>
                                    <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->bairroEndereco }}</td>
                                    <td>{{ $endereco->cidadeEndereco }}/{{ $endereco->ufEndereco }}</td>                                            
                                    <td>
                                        <a href="endereco/editar/{{ $endereco->codigoEndereco }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm">Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>                                  
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection