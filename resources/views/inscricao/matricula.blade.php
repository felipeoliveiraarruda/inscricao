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

                        @if ($sigla == 'PPGEM')
                            <p>Para a efetivação da matrícula entre os dias 11 e 13 de fevereiro de 2025, observar a documentação exigida indicada no Edital.</p>
                        @endif                            
                        
                        @if ($sigla == 'PPGPE')
                            <p>Para a efetivação da matrícula entre os dias 17 e 19 de fevereiro de 2025, observar a documentação exigida indicada no Edital.</p>
                        @endif
                        
                        @if ($sigla == 'PPGMAD')
                            <p>Para a efetivação da matrícula entre os dias 04 e 05 de fevereiro de 2025, observar a documentação exigida indicada no Edital.</p>                            
                        @endif

                        <p class="text-center">Local: Secretaria da Comissão de Pós-Graduação (CPG) - Área I</p>

                        <p class="text-center">Horário: 8h30 às 11h - 14h30 às 17h</p>

                        <p class="text-center">Início das Aulas: 17/03/2024</p>
                        
                        <p>Você deverá escolher uma ou mais disciplinas para cursar neste 1º semestre/2025.</p>

                        <p>Após a escolha da(s) disciplina(s) e a submissão, o {{ $sigla }} irá providenciar o Requerimento de Primeira Matrícula e enviar para o seu email.</p>

                        <p>De posse do requerimento, você poderá efetivar a matrícula, presencialmente, na Comissão de Pós-Graduação (CPG), na Área I da EEL/USP.</p>

                        <div class="form-group">  
                            <label class="font-weight-bold mr-2">Selecione a(s) disciplina(s)<span class="text-danger">*</span></label><br/>
                            @foreach($disciplinas as $disciplina)
                                @php
                                    $temp = \Uspdev\Replicado\Posgraduacao::oferecimento($disciplina['sgldis'], $disciplina['numofe']);
                                    $turma = $temp['espacoturma'][0];

                                    $total = count($temp['ministrante']) 
                                @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="{{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }}">
                                <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                    <p>
                                        @if ($disciplina['sgldis'] == 'PMD5508')
                                            {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }} - quarta-feira das {{ $turma['horiniofe'] }} as {{ $turma['horfimofe'] }}
                                        @else
                                            {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }} - {{ $turma['diasmnofe'] }} das {{ $turma['horiniofe'] }} as {{ $turma['horfimofe'] }}
                                        @endif
                                        
                                        @if ($disciplina['sgldis'] == 'PPE6402' && $sigla == 'PPGPE') 
                                        Disciplina obrigatória para alunos que ainda não cursaram
                                        @endif
                                    </p>
                                    <p><b>Ministrante(s): </b>

                                    @foreach($temp['ministrante'] as $ministrante)
                                        @if ($total == 1)
                                            {{ $ministrante['nompes'] }}
                                        @else
                                            @if ($loop->last)
                                                {{ $ministrante['nompes'] }}
                                            @else
                                                {{ $ministrante['nompes'] }} e
                                            @endif                                            
                                        @endif
                                    @endforeach   

                                    </p>
                                </label>
                                <hr/>
                            </div>
                            @endforeach

                            @if ($sigla == 'PPGEM')
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="EEL5000-1/6">
                                <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
                                EEL5000-1/6 Didática e Prática de Ensino de Engenharia (dia/horário a definir)
                                </label>
                            </div>
                            @endif
                        </div>
                        
                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Submeter</button>
                    </form>

                    <!-- Modal -->
                    @include('utils.loader')
                </div>
            </div>
        </div>
    </div>
</main>
@endsection