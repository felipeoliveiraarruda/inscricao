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
                                <div class="col-md-9">
                                    <h4>Dados Pessoais</h4>
                                </div>

                                <div class="col-md-3 text-right">
                                    @if (count($pessoais) == 0)
                                        <a href="pessoal/novo/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a>
                                    @endif
                                </div>
                                                                
                                <div class="col-md-12" >                                    
                                    <p></p>
       
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