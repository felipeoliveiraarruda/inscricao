@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Comprovante de Inscrição</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <form name="comprovante" id="comprovanteForm" method="POST" action="/inscricao/arquivos/salvar" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                            <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
                        </div>

                        <input type="hidden" name="codigoInscricao"     value="{{ $codigoInscricao }}">
                        <input type="hidden" name="codigoTipoDocumento" value="{{ $codigoTipoDocumento }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                        <a href="/inscricao/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-info btn-lg btn-block">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection                 




