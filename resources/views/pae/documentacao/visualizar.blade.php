@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="inscricao/{{$inscricao->codigoEdital}}/pae/{{$inscricao->codigoUsuario}}/visualizar" class="list-group-item list-group-item-action ">Voltar</a>
            </div> 
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Documentos Comprobatórios</h5>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">                             
                            @if (count($arquivos) > 0)
                            <div class="accordion" id="accordionArquivos">
                                @foreach($arquivos as $arquivo)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $arquivo->codigoTipoDocumento }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $arquivo->codigoTipoDocumento }}" aria-expanded="true" aria-controls="collapse{{ $arquivo->codigoTipoDocumento }}">
                                                {{ $arquivo->tipoDocumento }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse{{ $arquivo->codigoTipoDocumento }}" class="collapse show" aria-labelledby="heading{{ $arquivo->codigoTipoDocumento }}" data-parent="#accordionArquivos">
                                        <div class="card-body">
                                            @php
                                            $temp  = \App\Models\Arquivo::listarArquivosPae($inscricao->codigoPae, $arquivo->codigoTipoDocumento);
                                            $i = 1;
                                            @endphp

                                            @foreach($temp as $documento)

                                                <a href="{{ asset('storage/'.$documento->linkArquivo) }}" role="button" 
                                                    aria-pressed="true" class="btn btn-info btn-sm" target="_new" title="Visualizar">
                                                    <i class="far fa-eye"></i>Anexo {{ $i }}
                                                </a>
                                                @php
                                                    $i++;
                                                @endphp  
                                            @endforeach
                                        </div>
                                    </div>                                     
                                </div>
                                @endforeach
                            </div>
                            @endif                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection