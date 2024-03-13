@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Dados Pessoais</h5>
                @if (!empty($pessoais) == 0)
                    <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-info btn-sm float-right">Novo</a>
                @endif
                
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
                                    @if (!empty($pessoais))

                                        @if($pessoais->tipoDocumento == "RG")
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Nome</th>
                                                        <th scope="col">CPF</th>
                                                        <th scope="col">RG</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <td>{{ $pessoais->name }}</td>
                                                    <td class="text-center">{{ $pessoais->cpf }}</td>
                                                    <td class="text-center">{{ $pessoais->rg }}</td>                                          
                                                    <td class="text-center">
                                                        @if(Session::get('level') == 'user')
                                                            @if ($status == 'N')
                                                                <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                                    <i class="far fa-edit"></i>
                                                                </a> 
                                                            @endif 
                                                        @endif                                             
                                                    </td>
                                                </tr>
                                            </table>
                                        @else
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Nome</th>
                                                        <th scope="col">CPF</th>
                                                        <th scope="col">{{ $pessoais->tipoDocumento }}</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <td>{{ $pessoais->name }}</td>
                                                    <td class="text-center">{{ $pessoais->cpf }}</td>
                                                    <td class="text-center">{{ $pessoais->numeroDocumento }}</td>                                          
                                                    <td class="text-center">
                                                        @if(Session::get('level') == 'user')
                                                            @if ($status == 'N')
                                                                <a href="inscricao/{{ $codigoInscricao }}/pessoal/create" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Atualizar">
                                                                    <i class="far fa-edit"></i>
                                                                </a> 
                                                            @endif 
                                                        @endif                                             
                                                    </td>
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