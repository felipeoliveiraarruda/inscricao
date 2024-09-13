@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="{{ $link_voltar }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Editar Anexo</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="documento/{{ $arquivo->codigoArquivo }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        
                        <div class="form-group">
                            Arquivo Atual : <a href="storage/{{ $arquivo->linkArquivo }}" target="_new">Visualizar</a>
                            <input type="hidden" name="arquivoAtual" value="{{ $arquivo->linkArquivo }}">
                        </div>

                        <div class="form-group">
                            <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
                            @include('arquivo.documento.detalhes')
                        </div>

                        <input type="hidden" name="codigoInscricao"     value="{{ $codigoInscricao }}">
                        <input type="hidden" name="codigoTipoDocumento" value="{{ $arquivo->codigoTipoDocumento }}">
                        

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection