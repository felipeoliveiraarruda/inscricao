@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="realizadas" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Declaração de Acúmulos
                    @if (!empty($pessoais) == 0)
                        <a href="inscricao/{{ $codigoInscricao }}/declaracao-acumulos/create" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
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