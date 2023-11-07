@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/listar-inscritos/{{ $codigoEdital }}" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Desempenho Acadêmico</h5>
                
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
                    
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Programa</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $inscricao->name }}</td>
                            <td>{{ $inscricao->codpes }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                            <td>
                                <a href="{{ asset("storage/{$ficha->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ficha do Aluno"><i class="fas fa-university"></i></a>
                                
                                @if(!empty($historico))
                                <a href="{{ asset("storage/{$historico->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{ $historico->tipoDocumento}}"><i class="fas fa-book-open"></i></a>
                                @endif
                                
                                <a href="{{ asset("storage/{$lattes->linkArquivo}")}}" target="_new" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{ $lattes->tipoDocumento}}"><i class="far fa-file"></i></a>                                
                            </td>
                        </tr>
                    </table>
            
                    <form class="needs-validation" novalidate method="POST" action="admin/{{ $codigoPae }}/pae/desempenho">
                        @csrf
                        
                        @if ($editar)
                            @method('patch')
                        @endif

                        @include('admin.pae.partials.form_desempenho')
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Inserir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection