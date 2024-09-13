@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="admin/{{ $codigoEdital }}/pae/recurso" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">PAE - Avaliar Recurso</h5>
                
                <div class="card-body">                                            
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nº USP</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Programa</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>{{ $recurso->codpes }}</td>
                            <td>{{ $recurso->name }}</td>
                            <td>{{ $vinculo['nomcur'] }}-{{ $vinculo['nivpgm'] }}</td>
                        </tr>
                    </table>
            
                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="recurso/{{ $recurso->codigoRecurso }}">
                        @csrf
                        @method('patch')                                               
                        @include('admin.pae.recurso.partials.form')                                            
                    </form>

                    <!-- Modal -->
                    @include('utils.loader')
                </div>
            </div>
        </div>
    </div>
</main>

@endsection