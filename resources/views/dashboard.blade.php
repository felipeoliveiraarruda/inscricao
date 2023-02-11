@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Inscrições</h5>
                
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
                                        $hoje     = date('d/m/Y H:i:s');
                                    @endphp       
                                    <tr>
                                        <td>{{ $utils->obterNivelEdital($edital->nivelEdital) }} - {{ $curso['nomcur'] }}</td>
                                        <td>de {{ $edital->dataInicioEdital->format('d/m/Y') }} a {{ $edital->dataFinalEdital->format('d/m/Y') }}</td>

                                        @if ($hoje > $edital->dataInicioEdital->format('d/m/Y'))
                                            @if (Gate::check('admin') || Gate::check('gerente'))
                                                <td><a href="inscricao/{{ $edital->codigoEdital }}/listar">Lista de Inscritos</a></td>
                                            @else
                                                @if ($inscrito == 0)
                                                    <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a></td>
                                                @else
                                                    <td><a href="inscricao/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-success">Inscrito</a></td>
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