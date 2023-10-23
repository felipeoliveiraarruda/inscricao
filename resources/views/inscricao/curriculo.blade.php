@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/curriculo" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Currículo</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                       
                    <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/curriculo/store" enctype="multipart/form-data"> 
                        @csrf
                        
                        <div class="form-group">
                            <label for="codigoTipoDocumento">Tipo<span class="text-danger">*</span></label>
                            <select class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento" required>
                                <option value="">Selecione o Tipo de Documento</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->codigoTipoDocumento }}">{{ $tipo->tipoDocumento }}</option>
                            @endforeach
                            </select>
                        </div>                           
                        
                        <div class="form-group">
                            <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
                        </div>

                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                        <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection