@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/{{ $codigoPae }}/pae/analise/" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Análise do Currículo Lattes - {{ $arquivos[0]->tipoDocumento }}</h5>
                
                <div class="card-body">                                            
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
                                <a href='{{ asset("storage/{$ficha->linkArquivo}") }}' target="_new" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ficha do Aluno"><i class="fas fa-university"></i></a>
                                <a href='{{ asset("storage/{$lattes->linkArquivo}") }}' target="_new" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{ $lattes->tipoDocumento}}"><i class="far fa-file"></i></a>
                            </td>
                        </tr>
                    </table>
            
                    <form class="needs-validation" novalidate method="POST" action="admin/{{ $codigoPae }}/pae/analise/{{ $arquivos[0]->codigoTipoDocumento }}">
                        @csrf
                        
                        @method('patch')                               
                        
                        @include('admin.pae.partials.edit.form_edit_analise')                        
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection