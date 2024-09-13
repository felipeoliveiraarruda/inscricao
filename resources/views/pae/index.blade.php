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
                                <th scope="col">Já recebeu remuneração do PAE?</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->codpes }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                            <td>{{ ($inscricao->remuneracaoPae == "S") ? "Sim" : "Não" }}</td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="3">Documentos Comprobatórios
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
                </div>
            </div>
        </div>
    </div>
</main>

@endsection