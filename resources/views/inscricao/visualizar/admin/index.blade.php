@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            @include('inscricao.visualizar.admin.menu')  
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }}

                    @if ($status != 'N' && (Session::get('nivel') == 6))
                        <a href="inscricao/{{ $codigoInscricao }}/download" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Download">
                            <i class="fas fa-file-download"></i>
                        </a>
                    @endif
                </h5>

                <div class="card-body">   
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

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
                
                    <div class="row justify-content-center">
                        <table class="table table-striped">

                            @if ($status == 'P')
                            <tr>
                                <td colspan="2">

                                    <div class="container">
                                        <div class="row">
                                          <div class="col">
                                            <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/validar/{{ $codigoInscricao }}">                                    
                                                @csrf                   
                                                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Validar Inscrição</button>
                                                </form>
                                            </div>
                                            <div class="col">

                                                <a href="inscricao/{{ $codigoInscricao }}/devolver" role="button" aria-pressed="true" class="btn btn-primary btn-lg btn-block">
                                                    Devolver Inscrição
                                                </a>
                                            </div>
                                        </div>
                                      </div>
                                </td>
                            </tr>

                                <!-- Modal -->
                                @include('utils.loader')
                                
                            @endif

                            @foreach($arquivos as $arquivo)
                            <tr>
                                <td>{{ $arquivo->ordemTipoDocumento}} {{ $arquivo->tipoDocumento }}</td>
                                <td class="text-center">            
                                    <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @if (Session::get('nivel') == 6 && !empty($arquivo->linkArquivo))
                            <embed src="{{ asset('storage/'.$arquivo->linkArquivo) }}" width="1024" height="500" alt="pdf" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection