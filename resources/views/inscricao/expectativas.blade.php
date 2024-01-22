@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/expectativas" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Expectativas</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                       
                    <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/expectativas/store">                                    
                        @csrf
                        
                        <div class="form-group">
                            <label for="expectativasInscricao" class="font-weight-bold">{{ $titulo }}<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="expectativasInscricao" name="expectativasInscricao" rows="10" required></textarea>
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