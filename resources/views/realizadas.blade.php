@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('admin.menu.list')
        </div>

        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrições Realizadas</h5>
                
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @foreach ($editais as $edital)
                                @php
                                    $curso    = $utils->obterCurso($edital->codigoCurso);
                                    $inscrito = $inscricao->verificarInscricao($edital->codigoEdital, $user_id);

                                    $status   = $inscricao->obterStatusInscricao($inscrito);
                                    $hoje     = date('Y-m-d H:i:s');

                                    $inicio = $edital->dataInicioEdital;
                                    $final  = $edital->dataFinalEdital;

                                    $semestre = App\Models\Edital::obterSemestreAno($edital->codigoEdital, true);
                                @endphp

                                <tr>
                                    <td>{{ $edital->descricaoNivel }} - {{ $semestre }} - {{ $curso['nomcur'] }}</td>                                      
                                        @if ($edital->codigoNivel == 5)
<!--                                             @if (date('Y-m-d') < '2025-05-05')
                                                <td>Inscrições encerradas</td>
                                            @else
                                                @if ($status == 'P')
                                                    <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-info">Inscrição Pendente</a></td>
                                                @elseif ($status == 'N' && date('Y-m-d') < '2025-05-05')
                                                    <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-warning">Continuar inscrição</a></td>
                                                @elseif ($status == 'C')
                                                    <td>
                                                        <a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a>
                                                        <a href="inscricao/{{ $edital->codigoEdital }}/pae/comprovante" role="button" aria-pressed="true" target="_new" class="btn btn-info">Comprovante de Inscrição</a>

                                                        @if ($hoje > $edital->dataInicioRecurso && $hoje < $edital->dataFinalRecurso)
                                                        <a href="inscricao/{{ $edital->codigoEdital }}/pae/resultado" role="button" aria-pressed="true" class="btn btn-primary">Resultado</a>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endif -->
                                            <td>Inscrições encerradas</td>
                                        @else
                                            @if ($status == 'C')
                                                <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a></td>
                                            @else
                                                <td>Inscrições encerradas</td>
                                            @endif
                                        @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection