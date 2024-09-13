@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action active">Dados Pessoais</a>
                <a href="inscricao/{{ $codigoInscricao }}/escolar" class="list-group-item list-group-item-action">Resumo Escolar</a>
                <a href="inscricao/{{ $codigoInscricao }}/idioma" class="list-group-item list-group-item-action">Idiomas</a>
                <a href="inscricao/{{ $codigoInscricao }}/profissional" class="list-group-item list-group-item-action">Experiência Profissional</a>
                <a href="inscricao/{{ $codigoInscricao }}/ensino" class="list-group-item list-group-item-action">Experiência Em Ensino</a>         
                <a href="inscricao/{{ $codigoInscricao }}/financeiro" class="list-group-item list-group-item-action">Recursos Financeiros</a>
                <a href="inscricao/{{ $codigoInscricao }}/expectativas" class="list-group-item list-group-item-action">Expectativas</a> 
                <a href="inscricao/{{ $codigoInscricao }}/curriculo" class="list-group-item list-group-item-action">Currículo</a>
            
                @if (Session::get('nivel') == 2)
                    <a href="inscricao/{{ $codigoInscricao }}/pre-projeto" class="list-group-item list-group-item-action">Pré-projeto</a>
                @endif
            
                <a href="admin/listar-inscritos/{{ $inscricao->codigoEdital }}" class="list-group-item list-group-item-action">Voltar</a>

                @if ($inscricao->statusInscricao == 'P')
                    <a href="inscricao/validar/{{ $codigoInscricao }}" id="validarInscricao" class="btn btn-warning btn-block" role="button" aria-pressed="true">Validar Inscrição</a>
                    <a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">Ficha de Inscrição</a>
                    <br/>
                @endif
            </div>
        </div>     
        <div class="col-md-9">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>

            <div class="card bg-default">
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }}</h5>

                <div class="card-body">
                    @include($tipo)
                </div>
            </div>                    
        </div>        
    </div>
</main>

@include('utils.loader') 

@endsection