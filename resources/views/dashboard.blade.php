@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('admin.menu.list')
        </div>

        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Home</h5>
                
                <div class="card-body">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                @if ($msg == 'success')
                                <div class="alert alert-success" id="success-alert">
                                    {{ Session::get('alert-' . $msg) }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @else
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                                @endif
                            @endif
                        @endforeach
                    </div>

                    <table class="table">
                        <tbody>
                            @if (count($editais) == 0)
                                Nenhuma inscrição aberta
                            @else
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

                                        @if ($hoje < $inicio)
                                            <td>Aguarde a abertura das inscrições</td>
                                        @elseif ($hoje >= $inicio && $hoje <= $final)
                                            @if ($inscrito == 0)
                                                @if ($edital->codigoNivel == 5)
                                                <td>                                                                                                                
                                                    <a href="inscricao/{{ $edital->codigoEdital }}/pae/create" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a>
                                                </td>
                                                @else
                                                <td>                                                                                                                
                                                    <a href="inscricao/{{ $edital->codigoEdital }}/store" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a>
                                                </td>
                                               @endif
                                            @else
                                                @if ($edital->codigoNivel == 5)
                                                    @if ($status == 'P')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-info">Inscrição Pendente</a></td>
                                                    @elseif ($status == 'N')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-warning">Continuar inscrição</a></td>
                                                    @elseif ($status == 'C')
                                                        <td>
                                                            <a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a>
                                                            <a href="inscricao/{{ $edital->codigoEdital }}/pae/comprovante" role="button" aria-pressed="true" target="_new" class="btn btn-info">Comprovante de Inscrição</a>

                                                            @if ($hoje < $edital->dataFinalRecurso)
                                                            <a href="inscricao/{{ $edital->codigoEdital }}/pae/resultado" role="button" aria-pressed="true" class="btn btn-primary">Resultado</a>
                                                            @endif
                                                        </td>
                                                    @endif
                                                @else
                                                    @if ($status == 'P')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}/" role="button" aria-pressed="true" class="btn btn-info">Inscrição Pendente</a></td>
                                                    @elseif ($status == 'N')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-warning">Continuar inscrição</a></td>
                                                    @elseif ($status == 'C')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a></td>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <td>Inscrições encerradas</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection