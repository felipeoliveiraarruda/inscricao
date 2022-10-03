@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Inscrições Abertas</h5>
                
                <div class="card-body">
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
                                        <td>de {{ $edital->dataInicioEdital->format('d/m/Y') }} a {{ $edital->dataFinalEdital->format('d/m/Y') }}</td>
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