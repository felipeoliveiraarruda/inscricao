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
                                @php                                        
                                    $pos = \Uspdev\Replicado\Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
                                @endphp

                                <!-- if ($pos["tiping"] == 'REGULAR' && $pos["nomcur"] == "Projetos Educacionais de Ciências") -->
                                @if (count($regulamentos) > 0)
                                    @php                                        
                                        $regulamento = \App\Models\RegulamentosUsers::join('regulamentos', 'regulamentos_users.codigoRegulamento', '=', 'regulamentos.codigoRegulamento')
                                                                                    ->where('codigoUsuario', '=', Auth::user()->id)
                                                                                    ->first();
                                    @endphp
                                    <tr>
                                        <td>Regulamentação do Programa de Pós-Graduação em Projetos Educacionais de Ciências</td>
                                        <td>
                                            @if ($regulamento == '')
                                                <a href="regulamentacao/1/create" role="button" aria-pressed="true" class="btn btn-info">Acessar</a>
                                            @else
                                                <a href="regulamentacao/index" role="button" aria-pressed="true" class="btn btn-info">Acessar</a>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    Nenhuma inscrição aberta
                                @endif                                
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

                                        $pos = \Uspdev\Replicado\Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
                                    @endphp

                                    <tr>                                                                               
                                        @if ($liberado) 
                                            <td>{{ $edital->descricaoNivel }} - {{ $semestre }} - {{ $curso['nomcur'] }}</td>  
                                                                                  
                                            @if ($edital->codigoNivel == 5)
                                                    @if ($status == 'P')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-info">Em Análise</a></td>
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
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}/" role="button" aria-pressed="true" class="btn btn-info">Em Análise</a></td>
                                                    @elseif ($status == 'N')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-warning">Continuar inscrição</a></td>
                                                    @elseif ($status == 'C')
                                                        <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a></td>
                                                    @endif
                                                @endif
                                        @else   
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
                                                        @if ($edital->codigoNivel == 6)

                                                            @if (empty($pos))                                                                                                        
                                                                <td>-</td>
                                                            @else                                                                
                                                                @if ($pos['tiping'] == 'REGULAR')
                                                                <td>                                                                                                                
                                                                    <a href="inscricao/{{ $edital->codigoEdital }}/store" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a>
                                                                </td>
                                                                @else
                                                                    <td>Aluno não regular</td>
                                                                @endif
                                                            @endif

                                                        @else

                                                        <td>                                                                                                                
                                                            <a href="inscricao/{{ $edital->codigoEdital }}/store" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a>
                                                        </td>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($edital->codigoNivel == 5)
                                                        @if ($status == 'P')
                                                            <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-info">Em Análise</a></td>
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
                                                            <td><a href="inscricao/{{ $edital->codigoEdital }}/" role="button" aria-pressed="true" class="btn btn-info">Em Análise</a></td>
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