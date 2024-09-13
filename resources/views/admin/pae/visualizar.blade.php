@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/{{ $codigoPae }}/pae/analise/" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Análise do Currículo Lattes - {{ $arquivos[0]->tipoDocumento }}</h5>
                
                <div class="card-body">                                            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Programa</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $inscricao->name }}</td>
                            <td>{{ $inscricao->codpes }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                            <td>
                                <a href="{{ asset("storage/{$ficha->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ficha do Aluno"><i class="fas fa-university"></i></a>
                                
                                @if (!empty($lattes))
                                <a href="{{ asset("storage/{$lattes->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{ $lattes->tipoDocumento}}"><i class="far fa-file"></i></a>
                                @endif
                            </td>
                        </tr>
                    </table>
            
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col-5">#</th>
                                <th scope="col">Status</th>
                    
                                @if ($codigoTipoDocumento == 24 || $codigoTipoDocumento == 25)
                                    <th scope="col">Quantidade (em meses)</th>
                                @else
                                    <th scope="col">Quantidade</th>
                                @endif
                                <th scope="col">Justificativa</th>
                            </tr>
                        </thead>
                            @foreach($arquivos as $arquivo)
                            @php            
                                $analise = \App\Models\PAE\AnaliseCurriculo::obterAnalise($codigoPae, $arquivo->codigoArquivo);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" target="_new" title="Visualizar">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    @if ($analise->statusAnaliseCurriculo == 'R')
                                        Rejeitado
                                    @elseif ($analise->statusAnaliseCurriculo == 'S')
                                        Aceito
                                    @else 
                                        Alterado
                                    @endif
                                </td>
                                <td>
                                    {{ $analise->pontuacaoAnaliseCurriculo }}
                                </td>
                                <td>
                                    {{ $analise->justificativaAnaliseCurriculo }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection