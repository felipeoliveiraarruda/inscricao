@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/inscricao/{{ $codigoEdital }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Anexo</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <form method="POST" action="/documento/{{$codigoInscricao}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
                            @include('arquivo.documento.detalhes')
                        </div>

                        @if ($codigoTipoDocumento == 31)
                        <div class="form-group">  
                            <label for="bolsaRecursoFinanceiro" class="font-weight-bold">O Plano de Estudos possui Co-Orientador?<span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineCoorientador" id="inlineCoorientadorSim" value="S">
                                    <label class="form-check-label" for="inlineCoorientadorSim">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineCoorientador" id="inlineCoorientadorNao" value="N" checked>
                                    <label class="form-check-label" for="inlineCoorientadorNao">Não</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <input type="hidden" name="codigoInscricao"     value="{{ $codigoInscricao }}">
                        <input type="hidden" name="codigoTipoDocumento" value="{{ $codigoTipoDocumento }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection                 




