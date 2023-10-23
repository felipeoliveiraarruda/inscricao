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
                    @if (empty($financeiros->codigoRecursoFinanceiro))
                        <a href="inscricao/{{ $codigoInscricao }}/financeiro/create/" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right">Novo</a>
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
                                    @if (!empty($financeiros->codigoRecursoFinanceiro))

                                        <p><span class="font-weight-bold">Possui bolsa de estudos de alguma instituição?</span> Sim ({{ ($financeiros->bolsaRecursoFinanceiro == 'S' ? 'X' : ' ') }}) Não ({{ ($financeiros->bolsaRecursoFinanceiro == 'N' ? 'X' : ' ') }})</p>

                                        @if ($financeiros->bolsaRecursoFinanceiro == 'N')
                                            <p><span class="font-weight-bold">Deseja solicitar bolsa?</span> Sim ({{ ($financeiros->solicitarRecursoFinanceiro == 'S' ? 'X' : ' ') }}) Não ({{ ($financeiros->solicitarRecursoFinanceiro == 'N' ? 'X' : ' ') }})</p>
                                        @else
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Órgão Financiador</th>
                                                        <th scope="col">Início</th>
                                                        <th scope="col">Final</th>
                                                    </tr>
                                                </thead>                             
                                                <tr>
                                                    <td>{{ $financeiros->orgaoRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->inicioRecursoFinanceiro }}</td>
                                                    <td>{{ $financeiros->finalRecursoFinanceiro }}</td>
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