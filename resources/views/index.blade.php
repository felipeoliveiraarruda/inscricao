@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12">
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
                                            <a href="{{ $edital->linkEdital }}" target="_new">Procedimentos</a>
                                        </td>
                                        <td>de {{ $edital->dataInicioEdital->format('d/m/Y') }} a {{ $edital->dataFinalEdital->format('d/m/Y') }} 
                                        </td>
                                        @if ($edital->codigoNivel == 5 || $edital->codigoNivel == 6)
                                        <td> 
                                            <a class="login_logout_link" href="login">
                                                <i class="fas fa-sign-in-alt"></i> Entrar
                                            </a>
                                        </td>
                                        @else
                                        <td> 
                                            <a class="login_logout_link" href="acesso">
                                                <i class="fas fa-sign-in-alt"></i> Entrar
                                            </a>
                                        </td>
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Editais Encerrados</h5>
                
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @if (count($encerrados) == 0)
                                Nenhuma inscrição encerradas
                            @else
                                @foreach ($encerrados as $edital)
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

                    {{ $encerrados->appends(request()->query())->links() }} 
                </div>
            </div>
        </div>
    </div>    
</main>

@endsection