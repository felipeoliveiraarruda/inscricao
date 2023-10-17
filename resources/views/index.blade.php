@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Editais</h5>
                
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
                                        <td>{{ $edital->descricaoNivel }} - {{ $curso['nomcur'] }}</td>
                                        <td>
                                            <a href="{{ $edital->linkEdital }}" target="_new">Edital</a>
                                        </td>
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