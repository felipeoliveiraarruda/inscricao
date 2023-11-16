@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoEdital }}/pae/recurso" class="list-group-item list-group-item-action" style="background-color: #26385C; color: white;">Enviar Recurso</a>
                <a href="#" class="list-group-item list-group-item-action">Voltar</a>
            </div>
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
                <h5 class="card-header">PAE - Recurso
                    @if (empty($recurso->codigoRecurso))
                    <a href="inscricao/{{ $codigoPae }}/recurso/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>                        
                    </a>
                @endif
                </h5>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">                             
                            <div class="card-body">                                            
                                <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                        
                                <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoPae }}/recurso">
                                    @csrf
                                    <div class="form-group">
                                        <label for="justificativaRecurso" class="font-weight-bold">Justificativa</label><span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="justificativaRecurso" name="justificativaRecurso" rows="10" required></textarea>
                                    </div>                       
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar Recurso</button>
                                </form>

                                <!-- Modal -->
                                @include('utils.loader')                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection