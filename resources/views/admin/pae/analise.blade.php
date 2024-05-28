@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/listar-inscritos/{{ $codigoEdital }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">

                <h5 class="card-header">PAE - Análise do Currículo Lattes</h5>
                
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

                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

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
                                <a href="{{ $requerimento }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Requerimento de Inscrição"><i class="fas fa-eye"></i></a>                            
                                <a href="{{ asset("storage/{$ficha->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ficha do Aluno"><i class="fas fa-university"></i></a>

                                @if (!empty($lattes))
                                <a href="{{ asset("storage/{$lattes->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{ $lattes->tipoDocumento}}"><i class="far fa-file"></i></a>
                                @endif
                            </td>
                        </tr>
                    </table>
            
                    @if (count($arquivos) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Item(ns) Cadastrado(s)</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Pontuação</th>
                                <th scope="col">Nota</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        @foreach($arquivos as $arquivo)
                            @php            
                                $total     = \App\Models\Arquivo::listarArquivosPae($codigoPae, $arquivo->codigoTipoDocumento, true);
                                $avaliacao = \App\Models\PAE\Avaliacao::obterAvaliacao($codigoPae, $arquivo->codigoTipoDocumento);
                            @endphp
                        <tbody>
                            <tr>
                                <td>{{ $arquivo->tipoDocumento }}</td>
                                <td>{{ $total }}</td>

                                @if ($avaliacao == null) 
                                    <td></td>
                                    <td></td>
                                    <td>   
                                        @if ($docente == true)
                                        <a href="admin/{{$codigoPae}}/pae/analise/{{ $arquivo->codigoTipoDocumento }}" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Avaliar">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                        @endif
                                    </td>
                                @else
                                    <td>{{ $avaliacao->pontuacaoAvaliacao ?? '' }}</td>
                                    <td>{{ number_format($avaliacao->totalAvaliacao, 2, ',', '') ?? '' }}</td>
                                    <td>
                                        <a href="admin/{{$codigoPae}}/pae/analise/{{ $arquivo->codigoTipoDocumento }}/visualizar" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                        <!-- @if ($arquivo->codigoTipoDocumento == 24 || $arquivo->codigoTipoDocumento == 25)
                                        <a href="admin/{{$codigoPae}}/pae/analise/{{ $arquivo->codigoTipoDocumento }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif  -->
                                        <a href="admin/{{$codigoPae}}/pae/analise/{{ $arquivo->codigoTipoDocumento }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    @endif    
                </div>
            </div>
        </div>
    </div>
</main>

@endsection