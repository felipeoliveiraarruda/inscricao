@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item" aria-current="page">Usuários</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/admin/usuario/novo" class="list-group-item list-group-item-action">Novo Usuário</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Usuários</h4>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            @if (count($usuarios) == 0)
                <div class="alert alert-warning">Nenhum usuario cadastrado</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>    
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Permissão</th> 
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>                                                                            
                            <td>
                                @if ($usuario->can('admin'))
                                    <span class="badge badge-danger">admin</span>
                                @endif
                                @if ($usuario->can('gerente') && $usuario->cannot('admin'))
                                    <span class="badge badge-warning">gerente</span>
                                @endif
                                @if ($usuario->can('user') && $usuario->cannot('admin') && $usuario->cannot('gerente'))
                                    <span class="badge badge-success">usuário</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-danger" href="admin/usuario/recuperacao/{{ $usuario->id }}" role="button"><i class="fas fa-user-lock"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif                        
        </div>
    </div>
</main>    
@endsection