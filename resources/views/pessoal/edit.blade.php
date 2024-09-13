@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/pessoal/edit" class="list-group-item list-group-item-action active">Editar</a>
                <a href="{{ $link_voltar }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Editar Dados Pessoais</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="/pessoal/{{ $pessoais->codigoPessoal }}" enctype="multipart/form-data"" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        @include('pessoal.partials.form')
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection