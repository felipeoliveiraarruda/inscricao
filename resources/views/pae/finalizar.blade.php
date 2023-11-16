@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('pae.menu')  
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

            <div class="card bg-default">
                <h5 class="card-header">PAE - Processo Seletivo - Estágio Supervisonado em Docência - {{ $anosemestre }}</h5>
                
                <div class="card-body">                    
                    <div class="row">
                        <div class="col"> 
                            Caro(a) aluno(a)<br/><br/>

                            Você está enviando os seguintes documentos:<br/><br/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="3">Documentos Comprobatórios</th>
                                    </tr>
                                </thead>
                                @if (count($arquivos) > 0)
                                    <tr>
                                        <th scope="col">Tipo de Documento</th>
                                        <th scope="col">Quantidade</th>
                                    </tr>
                                    @foreach($arquivos as $arquivo)
                                    <tr>
                                        <td>{{ $arquivo->tipoDocumento }}</td>
                                        <td>{{ $arquivo->total }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </table>                     
                        </div>
                    </div>  
                    
                    <div class="row">
                        <div class="col"> 
                            Por favor, verifique se está correto, pois não será possível substituir ou anexar documentos após o envio. <br/><br/>

							<form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoEdital }}/pae/finalizar/store">
								@csrf                   

                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="confirmacaoEnvio" required>
                                    <label class="form-check-label" for="confirmacaoEnvio"> A documentação reportada acima está correta e, ao enviá-la, declaro que estou ciente e de acordo com todo o conteúdo do Edital do processo seletivo para o Estágio Supervisionado em Docência 20241.</label>
                                </div>
                               
                                <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
								
                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar Documentação</button>
							</form>

                            <!-- Modal -->
                            @include('utils.loader') 
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</main>

@endsection