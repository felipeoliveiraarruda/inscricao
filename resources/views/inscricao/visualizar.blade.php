@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                <h6 class="card-header">Documentos</h6>

                <div class="card-body">
                    @foreach($arquivos as $arquivo)
                        <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">{{ $arquivo->tipoDocumento }}</a>
                    @endforeach

                    @if ($inscricao->situacaoInscricao == 'P')
                        <a href="inscricao/comprovante/{{  $inscricao->codigoInscricao }}" target="_new" class="btn btn-primary btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>
                    @endif

                    <a href="admin/listar-inscritos/{{ $inscricao->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>
                </div>
            </div>
            
        </div>        
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }}</h5>

                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">E-mail</th>
                                <th scope="row">CPF</th>
                                <th scope="row">RG</th>
                                <th scope="row">Telefone</th>
                            </tr>
                            <tr>
                                <td>{{ $inscricao->email }}</td>
                                <td>{{ $inscricao->cpf }}</td>
                                <td>{{ $inscricao->rg }}</td>
                                <td>{{ $inscricao->telefone }}</td>
                            </tr>                            
                        </tbody>
                    </table>

                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">Endereço</th>
                                <th scope="row">Bairro</th>
                                <th scope="row">Cidade/UF</th>
                                <th scope="row">CEP</th>
                            </tr>
                            @foreach($enderecos as $endereco)
                            <tr>
                                <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->complementoEndereco }}</td>
                                <td>{{ $endereco->bairroEndereco }}</td>
                                <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                                <td>{{ $endereco->cepEndereco }}</td>
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