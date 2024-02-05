@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Dados Pessoais</h5>
                @if (!empty($pessoais) == 0)
                    <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right">Novo</a>
                @endif
                
                <div class="card-body">                    
                    <div class="row justify-content-center">        
                        <div class="col-md-12">
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
                                <div class="col-sm-12"> 
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
                                                @if (!empty($pessoais->codigoInscricaoPessoal) && (!empty($pessoais->codigoInscricaoDocumento)))
                                                    <i class="fa fa-check text-success"></i>
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif
                                            </td>                                            
                                            <td class="text-center">
                                                @if ($status == 'N')
                                                <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                    <i class="far fa-edit"></i>
                                                </a>  
                                                @endif                                             
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="card bg-default">
                                        <h5 class="card-header">Anexo(s) <span class="text-danger">*</span>
                                            @if ($status == 'N') 
                                                <a href="pessoal/anexo/{{$codigoInscricao}}" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                                                    <i class="fa fa-plus"></i>
                                                </a> 
                                            @endif
                                        </h5>
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
                                                        <tr>
                                                            <th>{{ $arquivo->tipoDocumento }}</th>
                                                            <td class="text-center">                          
                                                                @if (!empty($arquivo->codigoInscricaoArquivo))
                                                                    <i class="fa fa-check text-success"></i>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                @endif                                                                 
                                                            </td>
                                                            <td>
                                                                @if (empty($arquivo->codigoInscricaoArquivo))
                                                                    <a href="arquivo/{{ $arquivo->codigoArquivo }}/anexar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Anexar arquivo a inscrição">
                                                                        <i class="fas fa-paperclip"></i>
                                                                    </a>                                                                    
                                                                @endif

                                                                <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>

                                                                @if ($status == 'N')
                                                                    <a href="arquivo/{{ $arquivo->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                                                                        <i class="fa fa-wrench"></i>
                                                                    </a>
                                                                    <a href="arquivo/{{ $arquivo->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endif                                                         
                                                            </td>
                                                        </tr>
                                                    @endforeach                   
                                                    </table>  
                                                </div>                
                                            @endif

                                            <p>Documentos obrigatórios que devem ser anexados: Foto (jpg, png, bmp), CPF (pdf), RG/RNE/Passaporte (pdf)</p>
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