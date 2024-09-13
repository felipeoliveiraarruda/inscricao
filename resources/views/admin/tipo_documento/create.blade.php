@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item"><a href="/admin/tipo-documento">Tipo de Documento</a></li>
            <li class="breadcrumb-item" aria-current="page"></li>Novo</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/admin/tipo-documento/novo" class="list-group-item list-group-item-action active">Novo Tipo de Documento</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Tipo de Documento</h4>
            <hr/>

            <form class="needs-validation" novalidate method="POST" action="/admin/tipo-documento/salvar">
                @csrf
                
                <div class="form-group">
                    <label for="tipoDocumento">Tipo de Documento<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tipoDocumento" name="tipoDocumento" maxlength="255" required />
                </div>         

                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button><br/> 
            </form>                
        </div>
    </div>
</main>    
@endsection