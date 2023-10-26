@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrição</h5>
                
                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <div class="text-center">                            
                            <div class="btn-group btn-group-sm" role="group" aria-label="Status">
                                <button type="button" class="btn @if ($status == 'N') btn-success @else btn-secondary @endif">Aberta</button>
                                <button type="button" class="btn @if ($status == 'P') btn-success @else btn-secondary @endif">Pendente</button>
                                <button type="button" class="btn @if ($status == 'C') btn-success @else btn-secondary @endif">Confirmada</button>
                            </div> 

                            @if ($status == 'P')
                                <hr></hr>
                                <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>
                            
                            @elseif ($status == 'N')
                                <hr></hr>
                                @if ($total > 4)
                                    <p class="text-justify">Você deve enviar por e-mail, em uma única mensagem, o Requerimento de Inscrição assinado eletronicamente (assinatura eletrônica certificada: GOV.BR, DocuSign ou equivalente), para o endereço eletrônico {{ $email }}.</p> 
                                    <p class="text-justify">Colocar no assunto o seu nome e o texto "Seleção {{ $sigla }} {{ $anosemestre }}". Não serão aceitos fotos, "prints" ou digitalização do documento assinado eletronicamente.</p>
                                    <p class="text-justify text-danger font-weight-bold">Atenção: só clique no botão abaixo após ter certeza de ter feito o cadastro de todos os seus dados e o upload de todos os seu documentos obrigatórios conforme o edital.</p>
                                    
                                    <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>
                                @endif
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