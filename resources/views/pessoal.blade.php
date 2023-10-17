@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Inscrição</h5>
                
                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <div class="col-md-3 text-center">
                            <button type="button" class="btn btn-secondary btn-block">Dados Pessoais</button>
                            <button type="button" class="btn btn-secondary btn-block">Endereço</button>
                            <button type="button" class="btn btn-secondary btn-block">Pessoa Notificada em Caso de Emergencia</button>
                            <button type="button" class="btn btn-secondary btn-block">Resumo Escolar</button>
                            <button type="button" class="btn btn-secondary btn-block">Idioma</button>
                            <button type="button" class="btn btn-secondary btn-block">Experiencia Profissional</button>
                            <button type="button" class="btn btn-secondary btn-block">Experiencia Em Ensino</button>
                            <button type="button" class="btn btn-secondary btn-block">Disciplinas</button>
                            
                            <a href="inscricao/{{ $codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>
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

                            <div class="row">
                                <div class="col-sm-9">
                                    <h4>Dados Pessoais</h4>
                                </div>

                                <div class="col-sm-3 text-right">
                                    @if (!empty($pessoais) == 0)
                                        <a href="{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row">                             
                                <div class="col-sm-12" > 
                                    <p></p>
                                    @if (!empty($pessoais))
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">Nome</th>
                                                <th scope="col">CPF</th>
                                                <th scope="col">RG</th>
                                                <th scope="col">Status</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>{{ $pessoais->name }}</td>
                                            <td class="text-center">{{ $pessoais->cpf }}</td>
                                            <td class="text-center">{{ $pessoais->rg }}</td>
                                            <td class="text-center">
                                                @if (!empty($pessoais->codigoInscricaoPessoal) || (!empty($pessoais->codigoInscricaoDocumento)))
                                                    <i class="fa fa-check text-success"></i>
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif                                                
                                            </td>                                            
                                            <td class="text-center">
                                                <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-warning btn-sm" title="Editar">
                                                    Atualizar
                                                </a>                                               
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="card bg-default">
                                        <h5 class="card-header">Anexo(s) <a href="pessoal/anexo/{{$codigoInscricao}}" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a></h5>
                                        <div class="card-body">
                                            @if (count($arquivos) == 0)
                                                <div class="alert alert-warning">Nenhum documento cadastrado</div>                    
                                            @else                
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Arquivo</th>
                                                                <th scope="col">Status</th>                                                                
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>

                                                    @foreach ($arquivos as $arquivo)
                                                        @php
                                                            $arquivo_inscricao .= $arquivo->codigoArquivo."|";
                                                        @endphp
                                                        <tr>
                                                            <th>{{ $arquivo->tipoDocumento }}</th>
                                                            <td class="text-center">                          
                                                                @if (!empty($arquivo->codigoInscricaoArquivo))
                                                                    <i class="fa fa-check text-success"></i>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                @endif                                                                 
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                                <a href="arquivo/{{ $arquivo->codigoArquivo }}/editar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" title="Alterar">
                                                                    <i class="fa fa-wrench"></i>
                                                                </a>                                                              
                                                            </td>
                                                        </tr>
                                                    @endforeach                   
                                                    </table>  
                                                </div>                
                                            @endif
                                            @if (empty($arquivo->codigoInscricaoArquivo))
                                            <!-- Validation Errors -->
                                            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                            
                                            <form class="needs-validation" novalidate method="POST" action="inscricao/anexar">
                                                @csrf
                                                <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
                                                <input type="hidden" name="codigoArquivoInscricao" value="{{ $arquivo_inscricao }}">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Anexar documentos a inscrição</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>                                    
                                    @endif
                                </div>                                 
                            </div>                                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection