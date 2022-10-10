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
                            <button type="button" class="btn @if ($endereco == 1) btn-success @else btn-secondary @endif btn-block">Endereço</button>
                            <button type="button" class="btn @if ($cpf == 1) btn-success @else btn-secondary @endif btn-block">CPF</button>
                            <button type="button" class="btn @if ($rg == 1) btn-success @else btn-secondary @endif btn-block">RG / Passaporte / RNM</button>
                            <button type="button" class="btn @if ($historico == 1) btn-success @else btn-secondary @endif btn-block">Histórico Escolar</button>
                            <button type="button" class="btn @if ($diploma == 1) btn-success @else btn-secondary @endif btn-block">Diploma / Declaração de Conclusão</button>
                            <button type="button" class="btn @if ($curriculo == 1) btn-success @else btn-secondary @endif btn-block">Currículo Vitae / Currículo Lattes</button>
                            <button type="button" class="btn @if ($projeto == 1) btn-success @else btn-secondary @endif btn-block">Pré-projeto</button>
                            <button type="button" class="btn @if ($taxa == 1) btn-success @else btn-secondary @endif btn-block">Comprovante da Taxa de Inscrição</button>

                            <a href="inscricao/{{ $codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>

                            <!--@if ($total == 7 && $comprovante == 0)
                                <hr></hr>
                                <a href="/inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-block" role="button" aria-pressed="true">Comprovante de Inscrição</a>
                                <a href="/inscricao/arquivos/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-secondary btn-block" role="button" aria-pressed="true">Enviar Comprovante</a>                                
                            @endif-->
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
                                    <h4>Documentos</h4>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if ($total < 7)
                                        <a href="inscricao/arquivos/novo/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a>
                                    @endif
                                </div>
                                                                
                                <div class="col-md-12">                                    
                                    <p></p>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>    
                                                <th scope="col">Tipo</th>
                                                <th scope="col">Arquivo</th>
                                               <!--@ @if ($status == 'N') <th scope="col"></th> @endif-->
                                            </tr>
                                        </thead>
                                        @foreach ($arquivos as $arquivo)
                                        <tr>
                                            <td>{{ $arquivo->tipoDocumento }}</td>
                                            <td><a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" target="_new">Visualizar</a></td>
                                            <!--@if ($status == 'N')
                                            <td>
                                                <a href="inscricao/arquivos/editar/{{ $arquivo->codigoArquivo }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm">Editar</a>
                                            </td>
                                            @endif-->
                                        </tr>
                                        @endforeach
                                    </table>

                                    @if ($total == 7)
                                        @if ($status == 'N') 
                                            <p class="text-center">Para finalizar a inscrição, você deve imprimir o comprovante de inscrição, assinar e fazer o Upload.</p>
                                        @elseif ($status == 'P')
                                            <p class="text-center">Sua inscrição está pendente de confirmação, aguarde mais informações no seu e-mail</p>
                                        @endif
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