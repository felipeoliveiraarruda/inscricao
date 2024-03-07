@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="admin" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Deferimento</h5>

                <div class="card-body">                    
                    <div class="row justify-content-center">        
                        <div class="col-sm-12">
                            <div class="accordion" id="accordionDisciplinas">
                                @foreach($disciplinas as $disciplina)
                                    @php
                                        $temp = App\Models\Utils::obterOferecimentoPos($disciplina->codigoDisciplina, '04/03/2024', '16/06/2024');

                                        //dd($temp);

                                        $inscritos = App\Models\InscricoesDisciplinas::listarInscritosDisciplinas($codigoEdital, $disciplina->codigoDisciplina);
                                    @endphp

                                    @if (count($inscritos) > 0 && $temp['numvagespofe'] > 0)
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $disciplina->codigoDisciplina }}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $disciplina->codigoDisciplina }}" aria-expanded="true" aria-controls="collapse{{ $disciplina->codigoDisciplina }}">
                                                        {{ $temp['sgldis'] }}-{{ $temp['numseqdis'] }}/{{ $temp['numofe'] }} {{ $temp['nomdis'] }}
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapse{{ $disciplina->codigoDisciplina }}" class="collapse" aria-labelledby="heading{{ $disciplina->codigoDisciplina }}" data-parent="#accordionDisciplinas">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th scope="col">Nome</th>
                                                                    <th scope="col">E-mail</th>
                                                                    <th scope="col">Situação</th>
                                                                </tr>
                                                            </thead>
                                                            @foreach($inscritos as $inscrito) 
                                                                <tr>
                                                                    <td>{{ $inscrito->name }}</td>
                                                                    <td>{{ $inscrito->email }}</td>
                                                                    <td class="text-center">
                                                                        @if ($inscrito->statusDisciplina == 'N')
                                                                            Em Análise
                                                                        @elseif ($inscrito->statusDisciplina == 'D')
                                                                            Deferido
                                                                        @else
                                                                            Indeferido
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif        

                                @endforeach
                            </div>                                                      
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</main>

@endsection