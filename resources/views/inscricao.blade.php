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
                        <div class="col-md-5 text-center">                            
                            <div class="btn-group btn-group-sm" role="group" aria-label="Status">
                                <button type="button" class="btn @if ($status == 'N') btn-success @else btn-secondary @endif">Aberta</button>
                                <button type="button" class="btn @if ($status == 'P') btn-success @else btn-secondary @endif">Pendente</button>
                                <button type="button" class="btn @if ($status == 'C') btn-success @else btn-secondary @endif">Confirmada</button>
                            </div>  
                            <hr></hr>
                                @if ($status == 'P')
                                    <button type="button" class="btn @if ($endereco == 1) btn-success @else btn-secondary @endif btn-lg btn-block">Endereço</button>
                                    <button type="button" class="btn @if ($arquivo >= 7) btn-success @else btn-secondary @endif btn-lg btn-block">Documentos</button>
                                    <!--<a href="/inscricao/arquivos/comprovante/{{ $codigoInscricao }}" class="btn btn-secondary btn-lg btn-block" role="button" aria-pressed="true">Enviar Comprovante</a>-->
                                    <hr></hr>
                                    <p class="text-justify">Você deve enviar por e-mail, em uma única mensagem, o comprovante de pagamento da taxa de inscrição e o Requerimento de Inscrição (devidamente assinado), digitalizados, para o endereço eletrônico informado no formulário de inscrição, com cópia para ppgpe@eel.usp.br. Colocar no assunto o seu nome e o texto “Seleção PPGPE 2023”.</p>
                                    <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>                                    
                                @elseif ($status == 'N')
                                    <a href="inscricao/{{ $codigoInscricao }}/pessoal" role="button" aria-pressed="true" class="btn @if ($pessoal == 1) btn-success @else btn-secondary @endif btn-lg btn-block">Dados Pessoais</a>
                                    <!--<a href="inscricao/{{ $codigoInscricao }}/endereco" role="button" aria-pressed="true" class="btn @if ($endereco == 1) btn-success @else btn-secondary @endif btn-lg btn-block">Endereço</a>
                                    <a href="inscricao/{{ $codigoInscricao }}/documento" role="button" aria-pressed="true" class="btn @if ($total == 2) btn-success @else btn-secondary @endif btn-lg btn-block">Documentos</a>

                                    @if ($total == 2)
                                        <hr></hr>
                                        <p class="text-justify">Você deve enviar por e-mail, em uma única mensagem, o comprovante de pagamento da taxa de inscrição e o Requerimento de Inscrição (devidamente assinado), digitalizados, para o endereço eletrônico informado no formulário de inscrição, com cópia para ppgpe@eel.usp.br. Colocar no assunto o seu nome e o texto “Seleção PPGPE 2023”.</p>
                                        <p class="text-justify text-danger font-weight-bold">Atenção: só clique no botão abaixo após ter certeza de ter feito o upload de todos os seu documentos</p>
                                        <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>
                                    @endif-->
                                @elseif ($status == 'C')
                                    <p class="text-center">Sua inscrição para esse processo seletivo já está confirmada.</p>
                                    <p class="text-center">Aguarde mais informações no seu e-mail.</p>
                                    <a href="/" target="_new" class="btn btn-info btn-block" role="button" aria-pressed="true">Voltar</a>
                                @endif
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection