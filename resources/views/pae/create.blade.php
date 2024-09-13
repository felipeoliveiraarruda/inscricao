@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="inscricao/" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Processo Seletivo - Estágio Supervisonado em Docência - {{ $anosemestre }}</h5>
                
                <div class="card-body">                                            
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
            
                    <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoEdital }}/pae">
                        @csrf
                        @include('pae.partials.form')                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection