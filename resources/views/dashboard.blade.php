@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Inscrições Abertas - dashboard</h5>
                
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
                                        $curso = $utils->obterCurso($edital->codigoCurso);
                                    @endphp   
                                    <tr>
                                        <td>{{ $utils->obterNivelEdital($edital->nivelEdital) }} - {{ $curso['nomcur'] }}</td>
                                        <td>de {{ $edital->dataInicioEdital->format('d/m/Y - H:i') }} a {{ $edital->dataFinalEdital->format('d/m/Y - H:i') }}</td>
                                        <td>Inscreva-se</td>
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