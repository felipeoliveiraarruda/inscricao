@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('pae.menu')  
        </div>
        <div class="col-md-9">
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

            <div class="card bg-default">
                <h5 class="card-header">PAE - Processo Seletivo - Estágio Supervisonado em Docência - {{ $anosemestre }}</h5>
                
                <div class="card-body">                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Programa</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->codpes }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Participou do PAE anteriormente?</th>
                                <th scope="col">Recebeu remuneração do PAE?</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ ($inscricao->partipacaoPae  == "S") ? "Sim" : "Não" }}</td>
                            <td>{{ ($inscricao->remuneracaoPae == "S") ? "Sim" : "Não" }}</td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-4"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="2">Desempenho Acadêmico                                             
                                            @if (count($desempenhos) > 0)
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/desempenho/{{ $inscricao->codigoPae }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm float-right ml-1" title="Editar">
                                                <i class="fa fa-wrench"></i>
                                            </a>
                                            @endif
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/desempenho/create" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" title="Adicionar">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Conceito</th>
                                        <th scope="col">Quantidade</th>
                                    </tr>
                                </thead>
                                @foreach($desempenhos as $desempenho)
                                    <tr>
                                        <td>{{ $desempenho->descricaoConceito }}</td>
                                        <td>{{ $desempenho->quantidadeDesempenhoAcademico }} 
                                            <a href="inscricao/pae/desempenho/{{ $desempenho->codigoDesempenhoAcademico }}/destroy" role="button" aria-pressed="true" class="btn btn-danger btn-sm float-right" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esse Desempenho?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="3">Análise do Currículo Lattes 
                                            @if (count($analises) > 0)
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/analise/{{ $inscricao->codigoPae }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm float-right ml-1">
                                                <i class="fa fa-wrench"></i>
                                            </a>
                                            @endif
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/analise/create" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Item Avaliado</th>
                                        <th scope="col">Pontuação</th>
                                        <th scope="col">Anexo(s)</th>
                                    </tr>
                                </thead>
                                @foreach($analises as $analise)
                                    @php
                                        $tipo   = \App\Models\TipoDocumento::obterCodigoTipoDocumentoNome($analise->tipoAnalise);                                       
                                        $anexos = \App\Models\Arquivo::listarArquivosPae($inscricao->codigoPae, $tipo->codigoTipoDocumento, true);
                                    @endphp
                                    <tr>
                                        <td>{{ $analise->tipoAnalise }}</td>
                                        <td>{{ $analise->pontuacaoAnaliseCurriculo }}
                                            <a href="inscricao/pae/analise/{{ $analise->codigoAnaliseCurriculo }}/destroy" role="button" aria-pressed="true" class="btn btn-danger btn-sm float-right" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir essa Análise?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @if ($anexos == 0)
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/documentacao/{{ $analise->codigoAnaliseCurriculo }}/create" role="button" aria-pressed="true" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            @else
                                            <a href="#" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/documentacao/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fa fa-wrench"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection