@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Dados Pessoais</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    @if ($pessoais->codigoPessoal == '')
                    <form class="needs-validation" novalidate method="POST" action="{{ url('pessoal') }}">
                    @else
                    <form class="needs-validation" novalidate method="POST" action="{{ url('pessoal') }}/{{ $pessoais->codigoPessoal }}">
                        @method('patch')
                    @endif
                        @csrf
                        @include('pessoal.partials.form')                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection