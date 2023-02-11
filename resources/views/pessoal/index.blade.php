@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Endereços</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/endereco/novo" class="list-group-item list-group-item-action">Novo Endereço</a>
            </div>
        </div>

        <div class="col-sm-9">
            <h4>Endereço</h4>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>
            
            @if (count($enderecos) == 0)
                <div class="alert alert-warning">Nenhum dado pessoal cadastrado</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">CPF</th>
                            <th scope="col">RG</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    @foreach ($enderecos as $endereco)
                    <tr>
                        <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->bairroEndereco }}</td>
                        <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                        <td>{{ $endereco->cepEndereco }}</td>
                        <td>
                            <a href="endereco/editar/{{ $endereco->codigoEndereco }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm">Editar</a>
                            <a href="endereco/excluir/{{ $endereco->codigoEndereco }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                    @endforeach
                </table>  
            @endif                            
        </div>
    </div>
</main>

@endsection