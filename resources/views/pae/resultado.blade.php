@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="dashboard" class="list-group-item list-group-item-action">Voltar</a>
            </div>
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
                <h5 class="card-header">PAE - Processo Seletivo - Estágio Supervisonado em Docência - {{ $anosemestre }} - Resultado</h5>
                
                <div class="card-body">                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Programa</th>
                                <th scope="col">Avaliação</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $inscricao->codpes }}</td>
                            <td>{{ $inscricao->name }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                            <td>{{ $notaFinal }}</td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col"> 
                            <div class="card-body">
                                @if(!empty($recursos[0]))
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Recurso</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    @foreach($recursos as $temp)
                                        <tr>
                                            <td class="text-justify">{{ $temp->justificativaRecurso }}</td>
                                            <td>
                                                @if ($temp->statusRecurso == 'A')
                                                Aberta
                                                @elseif ($temp->statusRecurso == 'D')
                                                Deferida
                                                @else
                                                Indeferida
                                                @endif 
                                            </td>
                                        </tr>
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan="2">Análise</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td colspan="2" class="text-justify">{{ $temp->analiseRecurso }}</td>
                                        </tr>
                                    @endforeach
                                    </table>
                                @else
                                    @if($recurso)                                          
                                        <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                
                                        <form class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoPae }}/pae/recurso">
                                            @csrf
                                            <div class="form-group">
                                                <label for="justificativaRecurso" class="font-weight-bold">Recurso</label><span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="justificativaRecurso" name="justificativaRecurso" rows="5" required></textarea>
                                            </div>  
                                            
                                            <input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
                                            <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar Recurso</button>
                                        </form>

                                        <!-- Modal -->
                                        @include('utils.loader')
                                    @endif
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection