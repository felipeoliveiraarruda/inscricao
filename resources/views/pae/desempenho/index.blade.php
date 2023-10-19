@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('pae.menu')  
        </div>
        <div class="col-md-9">
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
                                                <a href="{{ asset('storage/'.$documento->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" target="_new" title="Visualizar">
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