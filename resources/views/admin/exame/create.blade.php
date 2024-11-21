@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href='{{ url("admin/{$codigoEdital}/exame") }}' class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>

        <div class="col-md-9">    
            <div class="card bg-default">
                <h5 class="card-header">{{ $curso }} - Exame de Proficiência
                </h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action=" {{ url('admin/exame/store') }}">
                        @csrf
                        <table class="table">                        
                            <thead>
                                <tr>    
                                    <th scope="col">Inscrição</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Resultado<span class="text-danger">*</span></th> 
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($inscritos as $inscrito)                     
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineResultado[{{ $inscrito->codigoInscricaoProficiencia }}]" id="inlineResultadoAprovado" value="S" @if ($inscrito->statusProficiencia == 'S') checked @endif>
                                                <label class="form-check-label" for="inlineResultadoAprovado">Aprovado</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineResultado[{{ $inscrito->codigoInscricaoProficiencia }}]" id="inlineResultadoReprovado" value="R" @if ($inscrito->statusProficiencia == 'R') checked @endif>
                                                <label class="form-check-label" for="inlineResultadoReprovado">Reprovado</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineResultado[{{ $inscrito->codigoInscricaoProficiencia }}]" id="inlineResultadoReprovado" value="A" @if ($inscrito->statusProficiencia == 'A') checked @endif>
                                                <label class="form-check-label" for="inlineResultadoReprovado">Ausente</label>
                                            </div>
                                        </div>      
                                    </td>
                                </tr>
                            @endforeach                        
                            </tbody>
                        </table>  

                        <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection