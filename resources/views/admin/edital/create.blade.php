@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item"><a href="/admin/edital">Edital</a></li>
            <li class="breadcrumb-item" aria-current="page"></li>Novo</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/admin/edital/create" class="list-group-item list-group-item-action active">Novo Edital</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Edital</h4>
            <hr/>

            <form class="needs-validation" novalidate method="POST" action="/admin/edital/salvar">
                @csrf

                <div class="form-group">
                    <label for="codigoCurso">Curso<span class="text-danger">*</span></label>
                    <select class="form-control" id="codigoCurso" name="codigoCurso" required>
                        <option value="">Selecione o Curso</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso['codcur'] }}">{{ $curso['nomcur'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nivelEdital">Nível<span class="text-danger">*</span></label>
                    <select class="form-control" id="nivelEdital" name="nivelEdital" required>
                        <option value="">Selecione o Nível</option>
                        <option value="AE">Aluno Especial</option>
                        <option value="DD">Doutorado Direto</option>
                        <option value="DF">Doutorado Fluxo Contínuo</option>
                        <option value="ME">Mestrado</option>
                    </select>
                </div>   
                
                <div class="form-group">
                    <label for="linkEdital">Link<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="linkEdital" name="linkEdital" maxlength="255" required />
                </div>
                
                <div class="form-group">
                    <label for="dataInicioEdital">Início<span class="text-danger">*</span></label>
                    <input type="datetime-local" step="1" class="form-control" id="dataInicioEdital" name="dataInicioEdital" value="" required />
                </div>

                <div class="form-group">
                    <label for="dataFinalEdital">Final<span class="text-danger">*</span></label>
                    <input type="datetime-local" step="1" class="form-control" id="dataFinalEdital" name="dataFinalEdital" value="" required />
                </div>                

                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button><br/> 
            </form>                
        </div>
    </div>
</main>    
@endsection