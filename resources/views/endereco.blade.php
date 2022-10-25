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
                            <button type="button" class="btn @if (count($enderecos) == 1) btn-success @else btn-secondary @endif btn-block">Endereço</button>
                            <button type="button" class="btn @if ($cpf == 1) btn-success @else btn-secondary @endif btn-block">CPF</button>
                            <button type="button" class="btn @if ($rg == 1) btn-success @else btn-secondary @endif btn-block">RG / Passaporte / RNM</button>
                            <button type="button" class="btn @if ($historico >= 1) btn-success @else btn-secondary @endif btn-block">Histórico Escolar</button>
                            <button type="button" class="btn @if ($diploma >= 1) btn-success @else btn-secondary @endif btn-block">Diploma / Declaração de Conclusão</button>
                            <button type="button" class="btn @if ($curriculo == 1) btn-success @else btn-secondary @endif btn-block">Currículo Vitae / Currículo Lattes</button>
                            <button type="button" class="btn @if ($projeto == 1) btn-success @else btn-secondary @endif btn-block">Pré-projeto</button>
                            <button type="button" class="btn @if ($taxa == 1) btn-success @else btn-secondary @endif btn-block">Comprovante da Taxa de Inscrição</button>                    
                            
                            <a href="inscricao/{{ $codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Voltar</a>
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
                                    <h4>Endereço</h4>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if (count($enderecos) == 0)
                                        <a href="inscricao/endereco/novo/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-info btn-sm">Novo</a>
                                    @endif
                                </div>
                                                                
                                <div class="col-md-12" >                                    
                                    <p></p>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Logradrouro</th>
                                                <th scope="col">Cidade/Uf</th>
                                                <th scope="col">CEP</th>
                                                <!--@<th scope="col"></th>-->
                                            </tr>
                                        </thead>
                                        @foreach ($enderecos as $endereco)
                                        <tr>
                                            <td>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->bairroEndereco }}</td>
                                            <td>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</td>
                                            <td>{{ $endereco->cepEndereco }}</td>
                                            <!--@<td>
                                                <a href="endereco/editar/{{ $endereco->codigoEndereco }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm">Editar</a>
                                            </td>-->
                                        </tr>
                                        @endforeach
                                    </table> 
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