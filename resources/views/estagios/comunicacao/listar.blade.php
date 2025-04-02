@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action">Home</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Processo Seletivo Estágiario Comunicação 2025</h5>
                
                <div class="card-body">
                    <table class="table">
                        @if (count($estagios) == 0)
                            <tr> 
                                <td>Esse processo seletivo não possui nenhuma inscrição</td>
                            </tr> 
                        @else
                            <thead>
                                <tr>
                                    <th scope="col">CPF</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($estagios as $estagio)
                                <tr>                                                                                
                                    <td>{{ $estagio->cpfEstagio }}</td>
                                    <td>{{ $estagio->nomeEstagio }}</td>
                                    <td>{{ $estagio->emailEstagio }}</td>
                                    <td>
                                        <a href="{{ url('estagios/comunicacao/'.$estagio->codigoEstagio.'/show') }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection