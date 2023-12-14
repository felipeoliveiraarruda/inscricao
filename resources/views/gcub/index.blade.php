@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row">
        <div class="col-sm-3 text-center">
            @include('gcub.menu')
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento de Primeira Matrícula</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="gcub/store">
                        @csrf
                        @include('gcub.partials.form')
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                    </form>
                </div>
            </div>            
        </div>
    </div>
</main>

@endsection