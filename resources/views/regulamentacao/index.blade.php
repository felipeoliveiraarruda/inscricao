@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="dashboard" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Regulamentação</h5>
                
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
                            
                            @if ($regulamento != '')
                               @if ($regulamento->linkArquivo == '')
                                    <p><b>Regulamentação do Programa de Pós-Graduação em Projetos Educacionais de Ciências</b> - <a href="imprimir/regulamentacao/{{ $regulamento->codigoRegulamento }}" target="_new">Gerar Requerimento para Assinatura</a></p>

                                    <p>Acesse o formulário no link acima devidamente preenchido, assine (aluno e orientado) com assinatura eletrônica certificada como Gov.br, DocuSign ou equivalente e faça o upload no campo abaixo.</p>

                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                        
                                    <form class="needs-validation" novalidate method="POST" action="regulamentacao/{{ $regulamento->codigoRegulamentoUser }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('patch')

                                        <div class="form-group">
                                            <label for="tipoEspecialPessoal" class="font-weight-bold">Upload do Formulário Assinado Eletrônicamente<span class="text-danger">*</span></label><br/>  
                                            
                                            <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>      
                                            <small id="arquivoAjudaBlock" class="form-text text-muted">
                                                
                                            </small>                      
                                        </div>

                                        <input type="hidden" name="codigoRegulamento" value="{{ $regulamento->codigoRegulamento }}">

                                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                                    </form>
                                @else
                                    <p>Regulamentação do Programa de Pós-Graduação em Projetos Educacionais de Ciências - <a href="{{ asset('storage/'.$regulamento->linkArquivo) }}" target="_new">Visualizar</a></p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection