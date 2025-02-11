@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <img width="300" height="auto" src="/images/logo-ppgem.png">
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Egressos</h5>
                
                <div class="card-body">                    

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    

                    <h4 class="text-justify mb-2">O PPGEM quer reunir seus ex-alunos! Você que obteve seu título de Mestre e/ou Doutor no PPGEM, atualize aqui o seu contato como Egresso USP!</h4>
                    
                    <form class="needs-validation" novalidate method="POST" action="{{ url('egressos') }}">
                        @csrf
                        @include('egressos.partials.form')
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
