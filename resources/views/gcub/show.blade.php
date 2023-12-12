@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row">
        <div class="col-sm-3">
            <img src="{{ $logo }}" class="img-fluid" height="400" width="400">            
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento de Primeira Matrícula - Imprimir</h5>
                
                <div class="card-body">
                    <div class="row justify-content-center">
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
                            <div class="col">
                                <a href="gcub/{{ $codigo }}/matricula" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento</a>
                            </div>
                            <div class="col">
                                <a href="gcub/{{ $codigo }}/bolsista" target="_new" class="btn btn-secondary btn-lg btn-block" role="button" aria-pressed="true">Bolsista</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</main>

@endsection