@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/disciplinas" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Disciplinas</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                       
                    <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/disciplina/store">                                    
                        @csrf

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col">  
                                    <label for="alunoEspecial" class="font-weight-bold">Já cursou disciplina como aluno especial?<span class="text-danger">*</span></label>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" name="alunoEspecial" id="alunoEspecialS" value="S">
                                        <label class="form-check-label" for="alunoEspecialS">Sim</label>
                                    </div>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" name="alunoEspecial" id="alunoEspecialN" value="N" checked>
                                        <label class="form-check-label" for="alunoEspecialS">Não</label>
                                    </div>
                                </div>
                                <div class="col"> 
                                    <div id="mostraAnoEspecial">                            
                                        <div class="form-group">
                                            <label for="dataAlunoEspecial" class="font-weight-bold">Semestre/Ano<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="dataAlunoEspecial" name="dataAlunoEspecial" value="{{  old('dataAlunoEspecial') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                        <div class="form-group">
                            <label for="expectativasInscricao" class="font-weight-bold">Selecione a(s) Disciplina(s)<span class="text-danger">*</span></label>
                            @if($limite == 0)
                                @foreach($disciplinas as $disciplina)

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="{{ $disciplina['sgldis'] }}">
                                    <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                        {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }}
                                    </label>
                                </div>
                                @endforeach
                            @else
                            @foreach($disciplinas as $disciplina)

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="{{ $disciplina['sgldis'] }}">
                                <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                    {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }}</label>
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                        <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection