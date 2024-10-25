@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inscrições</a></li>
            <li class="breadcrumb-item"><a href="{ url('admin/edital') }}">Edital</a></li>
            <li class="breadcrumb-item" aria-current="page"></li>Novo</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="{{ url('admin/edital/novo') }}" class="list-group-item list-group-item-action active">Novo Edital</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Edital</h4>
            <hr/>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                              
            <form class="needs-validation" novalidate method="POST" action="{{ url('admin/edital/salvar') }}">
                @csrf
                
                @include('admin.edital.partials.form')  

                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button><br/> 
            </form>                
        </div>
    </div>
</main>    
@endsection