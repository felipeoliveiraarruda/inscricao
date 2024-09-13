@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                @if ($inscricao->statusInscricao == 'P')
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                    
                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/validar/{{ $codigoInscricao }}">                                    
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Validar Inscrição</button>
                    </form>

                    <!-- Modal -->
                    @include('utils.loader')                        
                @endif

                <div class="list-group">
                      <!--<a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action">Dados Pessoais</a>
                    <a href="inscricao/{{ $codigoInscricao }}/escolar" class="list-group-item list-group-item-action">Resumo Escolar</a>
                    <a href="inscricao/{{ $codigoInscricao }}/idioma" class="list-group-item list-group-item-action">Idiomas</a>
                    <a href="inscricao/{{ $codigoInscricao }}/profissional" class="list-group-item list-group-item-action">Experiência Profissional</a>
                    <a href="inscricao/{{ $codigoInscricao }}/ensino" class="list-group-item list-group-item-action">Experiência Em Ensino</a>         
                    <a href="inscricao/{{ $codigoInscricao }}/financeiro" class="list-group-item list-group-item-action">Recursos Financeiros</a>
                    <a href="inscricao/{{ $codigoInscricao }}/expectativas" class="list-group-item list-group-item-action">Expectativas</a> 
                    <a href="inscricao/{{ $codigoInscricao }}/curriculo" class="list-group-item list-group-item-action">Currículo</a>
                
                    @if (Session::get('nivel') == 2)
                        <a href="inscricao/{{ $codigoInscricao }}/pre-projeto" class="list-group-item list-group-item-action">Pré-projeto</a>
                    @endif-->
                
                    <a href="{{ url()->previous() }}" class="list-group-item list-group-item-action">Voltar</a>
    
                    <!--if ($inscricao->statusInscricao == 'P')
                        <a href="inscricao/validar/{{ $codigoInscricao }}" id="validarInscricao" class="btn btn-warning btn-block" role="button" aria-pressed="true">Validar Inscrição</a>
                        <a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-block">Ficha de Inscrição</a>
                        <br/>
                    endif-->
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="flash-message">
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
            </div>

            <div class="card bg-default">
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }}</h5>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" colspan="8">Documentos
                                    @if(!empty($ficha))
                                        <a href="{{ $ficha }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary float-right">Ficha de Inscrição</a>
                                    @endif                                    
                                </th>                               
                            </tr>
                            <tr class="text-center">
                                <th scope="col">3.1.1<br/>Foto</th>
                                <th scope="col">3.1.2<br/>Ficha de Inscrição</th>
                                <th scope="col">3.1.3<br/>CPF/Passaporte</th>
                                <th scope="col">3.1.4<br/>RG</th>
                                <th scope="col">3.1.5<br/>RNE</th>
                                <th scope="col">3.1.6<br/>Certificado/Diploma</th>
                                <th scope="col">3.1.7<br/>Histórico Escolar</th>
                                <th scope="col">3.1.8<br/>Currículo Lattes/Vittae</th>
                                @if ($doutorado)
                                <th scope="col">3.2.1<br/>Pré-projeto</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <td>
                                @if(!empty($foto))
                                    <a href="{{ asset('storage/'.$foto) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Foto</a>
                                @else
                                    -                                    
                                @endif
                            </td>
                            <td>
                                @if(!empty($requerimento))
                                    <a href="{{ asset('storage/'.$requerimento) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Requerimento Assinado</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if(!empty($cpf))                            
                                    @if ($cpf->codigoTipoDocumento == 1)    
                                        <a href="{{ asset('storage/'.$cpf->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">CPF</a>
                                    @else
                                        <a href="{{ asset('storage/'.$cpf->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Passaporte</a>
                                    @endif
                                @else
                                    -                                    
                                @endif 
                            </td>
                            <td>
                                @if(!empty($rg))
                                    <a href="{{ asset('storage/'.$rg) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">RG</a>
                                @else
                                    -                                    
                                @endif                                
                            </td>
                            <td>
                                @if(!empty($rne))
                                    <a href="{{ asset('storage/'.$rne) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">RNE</a>
                                @else
                                    -                                    
                                @endif                                  
                            </td>
                            <td>
                                @if(!empty($diplomas))
                                    @foreach ($diplomas as $diploma)
                                        <a href="{{ asset('storage/'.$diploma->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Certificado/Diploma</a><br/><br/>
                                    @endforeach
                                @else
                                    -                                    
                                @endif                                
                            </td>
                            <td>
                                @if(!empty($historicos))
                                    @foreach ($historicos as $historico)
                                        <a href="{{ asset('storage/'.$historico->linkArquivo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Histórico Escolar</a><br/><br/>
                                    @endforeach
                                @else
                                    -                                    
                                @endif  
                            </td>
                            <td>
                                @if(!empty($curriculo))
                                    <a href="{{ asset('storage/'.$curriculo) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Currículo Lattes/Vittae</a>
                                @else
                                    -                                    
                                @endif
                            </td>
                            @if ($doutorado)
                            <td>
                                @if(!empty($projeto))
                                    <a href="{{ asset('storage/'.$projeto) }}" target="_new" role="button" aria-pressed="true" class="btn btn-sm btn-primary">Pré-projeto</a>
                                @else
                                    -                                    
                                @endif                                
                            </td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>                    
        </div>        
    </div>
</main>

@endsection