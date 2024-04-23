@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrição
                    <div class="btn-group btn-group-sm float-right" role="group" aria-label="Status">
                        <button type="button" class="btn @if ($status == 'N') btn-success @else btn-secondary @endif">Aberta</button>
                        <button type="button" class="btn @if ($status == 'P') btn-success @else btn-secondary @endif">Em Análise</button>
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

                    @if ($status == 'P')              
                        @if ($codigoEdital == 4)
                            <p class="text-justify">A relação dos Candidatos a Alunos Especiais com <b><i><u>Matrícula Pré-Aprovada</b></i></u> será divulgada na página da Comissão de Pós-Graduação-CPG (<a href="http://cpg.eel.usp.br/" target="_new"></a>http://cpg.eel.usp.br/</a>) em <b><i>“Notícias”</b></i>, até o dia 23 de Fevereiro de 2024.</p> 
                        @endif

                        @if ($codigoEdital == 5)
                            <p class="text-justify">A relação dos Candidatos a Alunos Especiais com <b><i><u>Matrícula Pré-Aprovada</b></i></u> será divulgada na página da Comissão de Pós-Graduação-CPG (<a href="http://cpg.eel.usp.br/" target="_new"></a>http://cpg.eel.usp.br/</a>) em <b><i>“Notícias”</b></i>, até o dia 21 de Fevereiro de 2024.</p> 
                        @endif

                        @if ($codigoEdital == 6 || $codigoEdital == 8)
                            <p>Sua inscrição está Em Análise. Aguarde informações pelo e-mail cadastrado.</p> 
                        @endif

                        <!--<a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new" class="btn btn-primary btn-lg" role="button" aria-pressed="true">Requerimento de Inscrição</a>-->
                    @elseif ($status == 'N')
                        @include('inscricao.visualizar.usuario')  
                        <!--@if ($total > 4)
                            <p class="text-justify">Você deve enviar o Requerimento de Inscrição assinado eletronicamente (assinatura eletrônica certificada: GOV.BR, DocuSign ou equivalente), através do menu do "Requerimento de Inscrição".</p> 
                            <p class="text-justify">Não serão aceitos fotos, "prints" ou digitalização do documento assinado eletronicamente.</p>                                    
                        @endif-->

                        @if ($codigoEdital == 8)
                            @php
                                $pos = \Uspdev\Replicado\Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
                            @endphp

                            <p class="text-justify">Verifique os seus dados, imprima e assine original ou eletronicamente por assinatura qualificada (por exemplo, Imprensa Oficial, Docusign etc.) o requerimento de inscrição e faça o upload do arquivo para finalizar sua inscrição.</p>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Nº USP</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Nível</th>
                                        <th scope="col">Orientador</th>
                                        <th scope="col">Imprimir</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>{{ Auth::user()->codpes }}</td>
                                    <td>{{ Auth::user()->name }}</td>
                                    <td>
                                        @if ($pos['nivpgm'] == 'DO') Doutorado @else Mestrado @endif
                                    </td>
                                    <td>{{ $pos['nompesori'] }}</td>
                                    <td>
                                        <a href="inscricao/{{ $codigoInscricao }}/proficiencia/imprimir" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Requerimento">
                                            <i class="fa fa-print"></i>  
                                        </a>                                    
                                    </td>
                                </tr>
                            </table>

                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                
                            <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/proficiencia/store" enctype="multipart/form-data"> 
                                @csrf
                                                                                
                                <div class="form-group">
                                    <label for="uploadArquivo" class="font-weight-bold">Upload Requerimento<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file" id="arquivo" name="arquivo" required accept="application/pdf">
                                </div>

                                <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                                <input type="hidden" name="codigoTipoDocumento" value="28">
                                <input type="hidden" name="codigoPessoaOrientador" value="{{ $pos['codpesori'] }}">

                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Finalizar Inscrição</button>
                            </form>

                            <!-- Modal -->
                            @include('utils.loader')
                        @endif

                    @elseif ($status == 'C')
                        <p class="text-justify">Sua inscrição para esse processo seletivo já está confirmada.</p>
                        <p class="text-justify">Aguarde mais informações no seu e-mail.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@endsection