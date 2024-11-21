@extends('layouts.app')

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">        
        <div class="col-sm-3">
            @include('admin.menu.list')           
        </div> 
        
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscritos</h5>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">                               
                            @if (count($inscritos) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Número de Inscrição</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">RG</th>
                                        <th scope="col">Ficha de Inscrição</th>
                                        <th scope="col">Currículo Lattes</th>
                                        <th scope="col">Pré-projeto</th>
                                        <th scope="col">Histórico</th>
                                    </tr>
                                </thead>

                                @foreach($inscritos as $inscrito)
                                    @php
                                        $curriculo    = \App\Models\Arquivo::obterArquivosCurriculo($inscrito->codigoInscricao);
                                        $requerimento = \App\Models\Arquivo::obterArquivosRequerimento($inscrito->codigoInscricao);
                                        $projeto      = \App\Models\Arquivo::obterArquivosPreProjeto($inscrito->codigoInscricao);
                                        $historico    = \App\Models\Arquivo::obterArquivosHistorico($inscrito->codigoInscricao);
                                    @endphp
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>{{ $inscrito->email }}</td>
                                    <td>{{ $inscrito->numeroDocumento }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$curriculo->linkArquivo) }}" role="button" aria-pressed="true"target="_new">
                                            Visualizar
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/'.$requerimento->linkArquivo) }}" role="button" aria-pressed="true" target="_new">
                                            Visualizar
                                        </a>                                        
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/'.$projeto->linkArquivo) }}" role="button" aria-pressed="true"  target="_new">
                                            Visualizar
                                        </a>                                        
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/'.$historico->linkArquivo) }}" role="button" aria-pressed="true"  target="_new">
                                            Visualizar
                                        </a>                                        
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            @endif                             
                        </div>                
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection