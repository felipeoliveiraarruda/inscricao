@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row">
        <div class="col-sm-3 text-center">
            @include('gcub.menu')
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento de Primeira Matrícula - Imprimir</h5>
                
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

                    <div class="row">
                        <div class="col">
                            <a href="gcub/{{ $codigo }}/matricula" target="_new" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Requerimento</a>
                        </div>
                        <div class="col">
                            <a href="gcub/{{ $codigo }}/bolsista" target="_new" class="btn btn-secondary btn-lg btn-block" role="button" aria-pressed="true">Bolsista</a>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>                                    
                                    <th scope="col">{{ __('Passaporte') }}</th>
                                    <th scope="col">{{ __('Nacionalidade') }}</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $dados->nomeAluno }}</td>
                                    <td>{{ $dados->emailAluno }}</td>
                                    <td>{{ $passaporte }}</td>
                                    <td>{{ $nacionalidade }}</td>                                    
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Data de Nascimento') }}</th>
                                    <th scope="col">{{ __('Nível') }}</th>
                                    <th scope="col" colspan="2">{{ __('Telefone') }}</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $dataNascimento }}</td>
                                    <td>{{ $dados->nivelPrograma }}</td>
                                    <td colspan="2">+{{ $dddPais}} {{ $dados->telefoneAluno }}</td>
                                </tr>
                            </tbody>  
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Maior nível de titulação obtido') }}</th>
                                    <th scope="col">{{ __('Instituição de Ensino Superior da Titulação') }}</th>                                    
                                    <th scope="col">{{ __('Ano de titulação') }}</th>
                                    <th scope="col">{{ __('País de Titulação') }}</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $dados->nivelTitulacao }}</td>
                                    <td>{{ $dados->iesTitulacao }}</td>
                                    <td>{{ $dados->anoTitulacao }}</td>
                                    <td>{{ $paisTitulacao }}</td>                                    
                                </tr>
                            </tbody>   
                            <thead>
                                <tr>
                                    <th scope="col" colspan="5">{{ __('Disciplina(s)') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados->disciplinasGcub as $disciplina)
                                
                                @php
                                    $temp = \Uspdev\Replicado\Posgraduacao::disciplina($disciplina);
                                @endphp
                                <tr>
                                    <td colspan="5">{{ $temp['sgldis'] }}-{{ $temp['numseqdis'] }} {{ $temp['nomdis'] }}</td>                                
                                </tr>
                                @endforeach
                            </tbody>                                                                                
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</main>

@endsection