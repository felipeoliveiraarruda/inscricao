@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento de Inscrição
                    @if (!empty($pessoais) == 0)
                        <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Imprimir">
                            <i class="fa fa-print"></i>  
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
                                   @if($requerimento == null)

                                        <!-- Validation Errors -->
                                        <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                                        
                                        <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
                                            @csrf
                                                                                            
                                            <div class="form-group">
                                                <label for="uploadArquivo" class="font-weight-bold">Upload Requerimento Assinado Digitalmente<span class="text-danger">*</span></label>
                                                <input type="file" class="form-control-file" id="arquivo" name="arquivo" required accept="application/pdf">
                                            </div>

                                            <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                                            <input type="hidden" name="codigoTipoDocumento" value="28">

                                            <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                                        </form>

                                        <!-- Modal -->
                                        @include('utils.loader')
                                    @else
                                    <label for="uploadArquivo" class="font-weight-bold">Requerimento Assinado Digitalmente</span></label>
                                    <p>
                                        <a href="{{ asset('storage/'.$requerimento->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                            <i class="far fa-eye"></i> Visualizar
                                        </a>
                                    </p>
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