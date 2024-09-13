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
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
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
                                        $status   = $inscricao->obterStatusInscricao($edital->codigoEdital, $user_id);
                                        $hoje     = date('d/m/Y H:i:s');
                                    @endphp       
                                    <tr>
                                        <td>{{ $edital->descricaoNivel }} - {{ $curso['nomcur'] }}</td>
                                        <td>de {{ $edital->dataInicioEdital->format('d/m/Y') }} a {{ $edital->dataFinalEdital->format('d/m/Y') }}</td>

                                        @if ($hoje > $edital->dataInicioEdital->format('d/m/Y H:i:s'))
                                            @if (Gate::check('admin') || Gate::check('gerente'))
                                                <td><a href="inscricao/{{ $edital->codigoEdital }}/listar">Lista de Inscritos</a></td>
                                            @else
                                                @if ($inscrito == 0)
                                                    @if ($edital->codigoNivel == 5)
                                                    <td>                                                                                                                
                                                        <a href="inscricao/{{ $edital->codigoEdital }}/pae/create" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se {{ $edital->nivelEdital }}</a>
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
                                                            <td><a href="inscricao/{{ $edital->codigoEdital }}/pae" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a></td>
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
                                            @endif
                                        @else 
                                            <td>Aguarde a abertura das inscricões</td>
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