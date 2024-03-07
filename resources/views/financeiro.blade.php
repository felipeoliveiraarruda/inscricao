@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Recursos Financeiros
                    @if (empty($financeiros->codigoInscricaoRecursoFinanceiro))
                        <a href="inscricao/{{ $codigoInscricao }}/financeiro/create/" role="button" aria-pressed="true" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Novo">
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
                                    @if (!empty($financeiros->codigoInscricaoRecursoFinanceiro))

                                        <p><span class="font-weight-bold">Possui bolsa de estudos de alguma instituição?</span> Sim ({{ ($financeiros->bolsaRecursoFinanceiro == 'S' ? 'X' : ' ') }}) Não ({{ ($financeiros->bolsaRecursoFinanceiro == 'N' ? 'X' : ' ') }})</p>

                                        @if ($financeiros->bolsaRecursoFinanceiro == 'S')
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Órgão Financiador</th>
                                                        <th scope="col">Tipo de Bolsa</th>
                                                        <th scope="col">Início</th>
                                                        <th scope="col">Final</th>
                                                    </tr>
                                                </thead>                             
                                                <tr>
                                                    <td>{{ $financeiros->orgaoRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->tipoBolsaFinanceiro }}</td>
                                                    <td>{{ $financeiros->inicioRecursoFinanceiro->format('d/m/Y') }}</td>
                                                    <td>{{ $financeiros->finalRecursoFinanceiro->format('d/m/Y') }}</td>
                                                </tr>                                                
                                            </table>
                                        @endif
                                        
                                        <p><span class="font-weight-bold">Deseja solicitar bolsa?</span> Sim ({{ ($financeiros->solicitarRecursoFinanceiro == 'S' ? 'X' : ' ') }}) Não ({{ ($financeiros->solicitarRecursoFinanceiro == 'N' ? 'X' : ' ') }})</p> 
                                        
                                        @if ($financeiros->solicitarRecursoFinanceiro == 'S')
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Ano</th>
                                                        <th scope="col">IES</th>
                                                        <th scope="col">Agência</th>
                                                        <th scope="col">Conta Corrente</th>
                                                        <th scope="col">Cidade/UF</th>
                                                    </tr>
                                                </thead>                             
                                                <tr>
                                                    <td>{{ $financeiros->anoTitulacaoRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->iesTitulacaoRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->agenciaRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->contaRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->localRecursoFinanceiro }}</td>
                                                </tr>                                                
                                            </table>
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