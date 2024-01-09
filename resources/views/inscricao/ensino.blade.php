@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/ensino" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Experiência Em Ensino</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                       
                    @if ($codigoExperiencia == '')      
                    <form class="needs-validation" novalidate method="POST" action="experiencia">  
                        @csrf
                        @include('experiencia.partials.form_ensino')  
                    @else
                    <form class="needs-validation" novalidate method="POST" action="experiencia/{{ $codigoExperiencia }}">
                        @method('patch')
                        @csrf
                        @include('experiencia.partials.form_ensino')   
                    @endif  
                        <input type="hidden" name="codigoTipoExperiencia" value="1">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection