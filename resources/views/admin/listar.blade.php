@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card bg-default">
                <h6 class="card-header">Pesquisar</h6>

                <div class="card-body"> 
                    <form method="get" action="admin/listar-inscritos/{{ $id }}/">
                        <div class="form-group">
                            <input type="text" placeholder="Nome ou E-mail" class="form-control" id="search" name="search" value="{{ request()->search }}" maxlength="255" required />
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Pesquisar</button>
                        @isset(request()->search)
                        <a href="admin/listar-inscritos/{{ $id }}/" role="button" aria-pressed="true" class="btn btn-info btn-block">Limpar</a>
                        @endisset
                    </form>
                </div>
            </div>
            <br/>
            <div class="card bg-default">
                <h6 class="card-header">E-mail</h6>

                <div class="card-body"> 
                    <a href="admin/enviar-email/{{ $id }}" role="button" aria-pressed="true" class="btn btn-info btn-block">Enviar E-mail</a>
                </div>
            </div>            
        </div>

        <div class="col-md-9">    
            <div class="card bg-default">
                <h5 class="card-header">{{ $curso }} - Lista de Inscritos</h5>
                
                <div class="card-body">                
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                            @endif
                        @endforeach
                    </div>                 

                    <table class="table">                        
                        <thead>
                            <tr>    
                                <th scope="col">Inscrição</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Situação</th> 
                                <th scope="col"></th> 
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($inscritos as $inscrito)
                                <tr>
                                    <td>{{ $inscrito->numeroInscricao }}</td>
                                    <td>{{ $inscrito->name }}</td>
                                    <td>{{ $inscrito->email }}</td>
                                    @if ($inscrito->situacaoInscricao == 'N')
                                        <th scope="col" class="text-info">Aberta</th>
                                    @elseif ($inscrito->situacaoInscricao == 'P')
                                        <th  scope="col" class="text-danger">Pendente</th>
                                    @else
                                        <th scope="col" class="text-success">Confirmada</th>
                                    @endif
                                    <td>
                                        <a href="inscricao/visualizar/{{ $inscrito->codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach                        
                        </tbody>
                    </table>

                    @empty(request()->search)
                        {{ $inscritos->appends(request()->query())->links() }} 
                    @endempty
                </div>
            </div>
        </div>
    </div>
</main>

@endsection