@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action">Dados Pessoais</a>
                <a href="inscricao/{{ $codigoInscricao }}/endereco" class="list-group-item list-group-item-action">Endereço</a>
                <a href="inscricao/{{ $codigoInscricao }}/emergencia" class="list-group-item list-group-item-action">Emergência</a>
                <a href="inscricao/{{ $codigoInscricao }}/escolar" class="list-group-item list-group-item-action">Resumo Escolar</a>
                <a href="inscricao/{{ $codigoInscricao }}/financeiro" class="list-group-item list-group-item-action">Recursos Financeiros</a>
                <a href="inscricao/{{ $codigoInscricao }}/obrigatorios" class="list-group-item list-group-item-action">Documentos Obrigatórios</a>
                <a href="/" class="list-group-item list-group-item-action">Voltar</a>
            </div>                 
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrição
                    <div class="btn-group btn-group-sm float-right" role="group" aria-label="Status">
                        <button type="button" class="btn @if ($status == 'N') btn-danger @else btn-secondary @endif">Aberta</button>
                        <button type="button" class="btn @if ($status == 'P') btn-warning @else btn-secondary @endif">Em Análise</button>
                        <button type="button" class="btn @if ($status == 'C') btn-success @else btn-secondary @endif">Confirmada</button>
                    </div>
                </h5>
                
                <div class="card-body">                    
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

                    @if ($nivel == 'DF')
                        @if ($status == 'N')
                            <p class="text-justify">Preencha seu dados através do menu do lado esquerdo. Após o cadastro de todos os dados obrigatórios clique no botão "Finalizar" para enviar a sua inscrição</p>

                            @if (Session::get('total')['coorientador'] == 'S')
                                @if (Session::get('total')['especial'] >= 5 && Session::get('total')['arquivo'] >= 14)
                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
                                        @csrf
                                                                                        
                                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                                        <input type="hidden" name="codigoTipo" value="ppgem">

                                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Finalizar</button>
                                    </form>

                                    <!-- Modal -->
                                    @include('utils.loader')
                                </div>
                                @endif
                            @else
                                @if (Session::get('total')['especial'] >= 5 && Session::get('total')['arquivo'] >= 10)
                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
                                        @csrf
                                                                                        
                                        <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                                        <input type="hidden" name="codigoTipo" value="ppgem">

                                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Finalizar</button>
                                    </form>

                                    <!-- Modal -->
                                    @include('utils.loader')
                                </div>
                                @endif
                            @endif   
                        @endif
                        @endif

                        @if ($status == 'P')
                            <p>Sua inscrição está Em Análise. Aguarde informações pelo e-mail cadastrado.</p> 
                        @endif

                        @if ($status == 'C')
                            <p class="text-justify">Sua inscrição para esse processo seletivo já está confirmada. Aguarde mais informações no seu e-mail.</p>
                        @endif



                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Dados Pessoais <i class="fa @if (Session::get('total')['pessoal'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i></th>
                                </tr>
                                <tr>
                                    <th scope="row">Endereço <i class="fa @if (Session::get('total')['endereco'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i></th>
                                </tr>
                                <tr>
                                    <th scope="row">Emergência <i class="fa @if (Session::get('total')['emergencia'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i></th>
                                </tr>
                                <tr>
                                    <th scope="row">Resumo Escolar <i class="fa @if (Session::get('total')['escolar'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i></th>
                                </tr>
                                <tr>
                                    <th scope="row">Recursos Financeiros <i class="fa @if (Session::get('total')['financeiro'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i></th>
                                </tr>

                                @if (Session::get('total')['coorientador'] == 'S')
                                <tr>
                                    <th scope="row">Documentos Obrigatórios <i class="fa @if (Session::get('total')['arquivo'] >= 14) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></th>
                                </tr>
                                @else
                                <tr>
                                    <th scope="row">Documentos Obrigatórios <i class="fa @if (Session::get('total')['arquivo'] >= 10) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></th>
                                </tr>    
                                @endif                          
                            </tbody>
                        </table>

                    {{-- @if ($status == 'P')              
                        @if ($codigoEdital == 4)
                            <p class="text-justify">A relação dos Candidatos a Alunos Especiais com <b><i><u>Matrícula Pré-Aprovada</b></i></u> será divulgada na página da Comissão de Pós-Graduação-CPG (<a href="http://cpg.eel.usp.br/" target="_new"></a>http://cpg.eel.usp.br/</a>) em <b><i>“Notícias”</b></i>, até o dia 23 de Fevereiro de 2024.</p> 
                        @endif

                        @if ($codigoEdital == 5)
                            <p class="text-justify">A relação dos Candidatos a Alunos Especiais com <b><i><u>Matrícula Pré-Aprovada</b></i></u> será divulgada na página da Comissão de Pós-Graduação-CPG (<a href="http://cpg.eel.usp.br/" target="_new"></a>http://cpg.eel.usp.br/</a>) em <b><i>“Notícias”</b></i>, até o dia 21 de Fevereiro de 2024.</p> 
                        @endif

                        @if ($codigoEdital == 6)
                            <p>Sua inscrição está Em Análise. Aguarde informações pelo e-mail cadastrado.</p> 
                        @endif

                        <!--<a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg" role="button" aria-pressed="true">Requerimento de Inscrição</a>-->
                    @elseif ($status == 'N')
                        {{-- @include('inscricao.visualizar.usuario') 
                    @elseif ($status == 'C')
                        <p class="text-justify">Sua inscrição para esse processo seletivo já está confirmada.</p>
                        <p class="text-justify">Aguarde mais informações no seu e-mail.</p>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</main>

@endsection