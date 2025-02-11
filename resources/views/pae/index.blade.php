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
                <h5 class="card-header">PAE - Processo Seletivo - EstÃ¡gio Supervisonado em DocÃªncia - {{ $anosemestre }}</h5>
                
                <div class="card-body">                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">NÂº USP</th>
                                <th scope="col">Programa</th>
                                <th scope="col">JÃ¡ recebeu remuneraÃ§Ã£o do PAE?</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->codpes }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                            <td>{{ ($inscricao->remuneracaoPae == "S") ? "Sim" : "NÃ£o" }}</td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="3">Documentos ComprobatÃ³rios
                                            @if (count($arquivos) > 0)
                                            <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/documentacao" role="button" aria-pressed="true" class="btn btn-primary btn-sm float-right ml-1" target="_new" title="Visualizar">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            @endif
                                            @if ($inscricao->statusInscricao == 'N')    
                                                <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/documentacao/create" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                @if (count($arquivos) > 0)
                                    <tr>
                                        <th scope="col">Tipo de Documento</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col"></th>
                                    </tr>
                                    @foreach($arquivos as $arquivo)
                                    <tr>
                                        <td>{{ $arquivo->tipoDocumento }}</td>
                                        <td>{{ $arquivo->total }}</td>
                                        <td>
                                            @if ($inscricao->statusInscricao == 'N')
                                                <a href="inscricao/{{ $inscricao->codigoEdital }}/pae/documentacao/{{ $arquivo->codigoTipoDocumento }}/edit" role="button" aria-pressed="true" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fa fa-wrench"></i>
                                                </a>
                                                <a href="inscricao/{{$inscricao->codigoEdital }}/pae/documentacao/{{ $arquivo->codigoTipoDocumento }}/destroy" role="button" aria-pressed="true" class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esse(s) Documento(s)?')">
                                                    <i class="fa fa-trash"></i>                                        
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </table>                     
                        </div>
                    </div>

                    @if ($recurso->codigoRecurso > 0)
                    <div class="row">
                        <div class="col"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="3">Recurso</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <th scope="col">Justificativa</th>
                                </tr>
                                <tr>
                                    <td class="text-justify">{{ $recurso->justificativaRecurso }}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Status</th>
                                </tr>
                                <tr>
                                    <td class="text-justify">
                                        @if ($recurso->statusRecurso == 'N')
                                            Aberta
                                        @elseif ($recurso->statusRecurso == 'D')
                                            Deferido
                                        @else
                                            Indeferido
                                        @endif 
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">AnÃ¡lise</th>
                                </tr>
                                <tr>
                                    <td class="text-justify">
                                    {{ $recurso->analiseRecurso }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@endsection