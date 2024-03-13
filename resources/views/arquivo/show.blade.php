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
                <h5 class="card-header">Arquivos</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="/inscricao/arquivos/salvar" enctype="multipart/form-data">
                        @csrf
                                        
                        @if ($codigoTipoDocumento == 2)
                            @include('arquivo.partials.rg')
                        @elseif ($codigoTipoDocumento == 6)
                            @include('arquivo.partials.diploma')     
                        @elseif ($codigoTipoDocumento == 8)
                            @include('arquivo.partials.curriculo')
                        @else
                            <input type="hidden" name="codigoTipoDocumento" value="{{ $codigoTipoDocumento }}">  
                        @endif

                        <div class="form-group">
                            <input type="file" class="form-control-file" id="arquivo" name="arquivo">
                        </div>  

                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button><br/> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection