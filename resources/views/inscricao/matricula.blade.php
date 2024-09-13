@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="dashboard" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento Primeira Matrícula</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/matricula">
                        @csrf

                        <p>Para a efetivação da matrícula entre os dias 05 e 06 de fevereiro de 2024, observar a documentação exigida indicada no Edital.</p>

                        <p class="text-center">Local: Secretaria da Comissão de Pós-Graduação (CPG) - Área I</p>

                        <p class="text-center">Horário: 8h30 às 11h - 14h30 às 17h</p>

                        <p class="text-center">Início das Aulas: 04/03/2024</p>
                        
                        <p>Você deverá escolher uma ou mais disciplinas para cursar neste 1º semestre/2024.</p>

                        <p>Após a escolha da(s) disciplina(s) e a submissão, o PPGEM irá providenciar o Requerimento de Primeira Matrícula e enviar para o seu email.</p>

                        <p>De posse do requerimento, você poderá efetivar a matrícula, presencialmente, na Comissão de Pós-Graduação (CPG), na Área I da EEL/USP.</p>

                        <div class="form-group">  
                            <label class="font-weight-bold mr-2">Selecione a(s) disciplina(s)<span class="text-danger">*</span></label><br/>
                            @foreach($disciplinas as $disciplina)
                                                
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="{{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }}">
                                <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                    {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }}</label>
                                </label>
                            </div>
                            @endforeach

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="EEL5000-1/6">
                                <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                EEL5000-1/6 Didática e Prática de Ensino de Engenharia</label>
                                </label>
                            </div>
                        </div>
                        
                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>

                    <!-- Modal -->
                    @include('utils.loader')
                </div>
            </div>
        </div>
    </div>
</main>
@endsection