@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Currículo
                    @if (empty($curriculo->codigoArquivo))
                        <a href="inscricao/{{ $codigoInscricao }}/curriculo/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                            <i class="fa fa-plus"></i>
                        </a>
                    @endif
                </h5>

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
                                    @if (!empty($curriculo->codigoArquivo))
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Arquivo</th>
                                                        <th scope="col">Status</th>                                                                
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <th>{{ $curriculo->tipoDocumento }}</th>
                                                    <td class="text-center">                          
                                                        @if (!empty($curriculo->codigoInscricaoArquivo))
                                                            <i class="fa fa-check text-success"></i>
                                                        @else
                                                            <i class="fa fa-times text-danger"></i>
                                                        @endif                                                                 
                                                    </td>
                                                    <td>
                                                        @if (empty($curriculo->codigoInscricaoArquivo))
                                                            <a href="arquivo/{{ $curriculo->codigoArquivo }}/anexar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Anexar arquivo a inscrição">
                                                                <i class="fas fa-paperclip"></i>
                                                            </a>                                                                    
                                                        @endif

                                                        <a href="{{ asset('storage/'.$curriculo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        @if ($status == 'N')
                                                            <a href="arquivo/{{ $curriculo->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                                                                <i class="fa fa-wrench"></i>
                                                            </a>
                                                            <a href="arquivo/{{ $curriculo->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endif                                                         
                                                    </td>
                                                </tr>                
                                            </table>
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