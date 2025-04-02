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
                <h5 class="card-header">Processo Seletivo Estágios</h5>
                
                <div class="card-body">
                <table class="table">
                        <tbody>
                            @if (count($editais) == 0)
                                <tr> 
                                    <td>Nenhuma processo seletivo aberto</td>
                                </tr> 
                            @else
                                @foreach ($editais as $edital)
                                    @php
                                        $hoje     = date('Y-m-d H:i:s');

                                        $inicio = $edital->dataInicioEditalEstagio;
                                        $final  = $edital->dataFinalEditalEstagio;
                                    @endphp

                                    <tr>                                                                                
                                        <td>{{ $edital->descricaoEditalEstagio }}</td>                                  
                                        @if ($hoje < $inicio)
                                            <td>Aguarde a abertura das inscrições</td>
                                        @elseif ($hoje >= $inicio && $hoje <= $final)
                                            <td>
                                                <a href="estagios/{{ $edital->linkEditalEstagio }}" role="button" aria-pressed="true" class="btn btn-info">Inscreva-se</a>  
                                            </td>
                                        @else 
                                            <td>Inscrições encerradas</td>
                                        @endif
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

