@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                <div class="list-group">
                    <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action">Dados Pessoais</a>
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
    
                    <!--if ($inscricao->statusInscricao == 'P')
                        <a href="inscricao/validar/{{ $codigoInscricao }}" id="validarInscricao" class="btn btn-warning btn-block" role="button" aria-pressed="true">Validar Inscrição</a>
                        <a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">Ficha de Inscrição</a>
                        <br/>
                    endif-->
                </div>
            </div>

            <!-- Modal -->
            @include('utils.loader')
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
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }}
                    @if ($inscricao->statusInscricao == 'P')
                        <a href="inscricao/validar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm float-right">Validar Inscrição</a>
                    @endif
                </h5>

                <div class="card-body">


                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Documentos</th>                               
                            </tr>
                            <tr class="text-center">
                                <th scope="col">3.1.1</th>
                                <th scope="col">3.1.2</th>
                                <th scope="col">3.1.3</th>
                                <th scope="col">3.1.4</th>
                                <th scope="col">3.1.5</th>
                                <th scope="col">3.1.6</th>
                                <th scope="col">3.1.7</th>
                                <th scope="col">3.1.8</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <td></td>
                            <td><a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Ficha de Inscrição</a></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tbody>
                    </table>



                    @foreach($arquivos as $arquivo)
                        <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">{{ $arquivo->tipoDocumento }}</a>
                    @endforeach
                </div>
            </div>                    
        </div>        
    </div>
</main>

@endsection