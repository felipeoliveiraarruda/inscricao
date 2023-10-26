@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                <h6 class="card-header">Documentos</h6>

                <div class="card-body">
                    @if ($inscricao->statusInscricao == 'P')
                        <a href="inscricao/validar/{{ $codigoInscricao }}" id="validarInscricao" class="btn btn-warning btn-block" role="button" aria-pressed="true">Validar Inscrição</a>
                    @endif

                    <a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">Ficha de Inscrição</a>

                    @foreach($arquivos as $arquivo)
                        <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">{{ $arquivo->tipoDocumento }}</a>
                    @endforeach

                    <a href="admin/listar-inscritos/{{ $inscricao->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="loaderModal" tabindex="-1" aria-labelledby="loaderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">		
                        <div class="modal-body text-justify">
                            <div id="loader">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-secondary" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-success" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-danger" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-warning" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-info" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-light" role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="spinner-grow text-dark" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            
        </div>        
        <div class="col-md-9">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>

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
                            <tr>
                                <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->complementoEndereco }}</td>
                                <td>{{ $endereco->bairroEndereco }}</td>
                                <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                                <td>{{ $endereco->cepEndereco }}</td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>                    
        </div>        
    </div>
</main>

@endsection