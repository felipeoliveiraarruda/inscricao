@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrição

                    <div class="btn-group btn-group-sm float-right" role="group" aria-label="Status">
                        <button type="button" class="btn @if ($status == 'N') btn-success @else btn-secondary @endif">Aberta</button>
                        <button type="button" class="btn @if ($status == 'P') btn-success @else btn-secondary @endif">Pendente</button>
                        <button type="button" class="btn @if ($status == 'C') btn-success @else btn-secondary @endif">Confirmada</button>
                    </div>
                </h5>
                
                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <div class="text-justify">
                            @if ($status == 'P')
                                <hr></hr>
                                <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento de Inscrição</a>
                            @elseif ($status == 'N')
                                <hr></hr>
                                @if ($total > 4)
                                    <p class="text-justify">Você deve enviar o Requerimento de Inscrição assinado eletronicamente (assinatura eletrônica certificada: GOV.BR, DocuSign ou equivalente), através do menu do "Requerimento de Inscrição".</p> 
                                    <p class="text-justify">Não serão aceitos fotos, "prints" ou digitalização do documento assinado eletronicamente.</p>                                    
                                @endif
                            @elseif ($status == 'C')
                                <p class="text-justify">Sua inscrição para esse processo seletivo já está confirmada.</p>
                                <p class="text-justify">Aguarde mais informações no seu e-mail.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection