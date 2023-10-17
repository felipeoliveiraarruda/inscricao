@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoEdital }}/pae" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Documentos Comprobatórios</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoPae }}/pae/{{ $codigoTipoDocumento }}/documentacao" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                    
                        <div class="form-group"> 
                            <label for="codigoTipoDocumento" class="font-weight-bold">Tipo de Documento<span class="text-danger">*</span></label>
                            <select class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento" required disabled>
                                <option value="">Selecione o Tipo de Documento</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->codigoTipoDocumento }}" {{ old('codigoTipoDocumento') == $codigoTipoDocumento ? "selected" : $tipo->codigoTipoDocumento == $codigoTipoDocumento ? "selected" : "" }}>{{ $tipo->tipoDocumento}}</option>
                                @endforeach
                            </select>
                        </div>

                        @foreach($arquivos as $arquivo)
                        <div class="form-group">
                            <label for="arquivo" class="font-weight-bold">Anexo</label>
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="arquivo" name="arquivo[{{ $arquivo->codigoArquivo }}]" data-show-upload="false" data-show-caption="true">
                            </div>
                        </div>

                        <div class="form-group">
                            Arquivo Atual : <a href="storage/{{ $arquivo->linkArquivo }}" target="_new">Visualizar</a>
                            <input type="hidden" name="arquivoAtual[{{ $arquivo->codigoArquivo }}]" value="{{ $arquivo->linkArquivo }}">
                        </div>
                        @endforeach

                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
                        <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection



