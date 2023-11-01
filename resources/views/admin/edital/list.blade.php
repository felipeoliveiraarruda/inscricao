@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item"><a href="admin/edital">Edital</a></li>
            <li class="breadcrumb-item" aria-current="page">Inscritos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            
        </div>      
        
        <div class="col-sm-9">
            <h4>Edital</h4>
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

            @if (count($inscritos) == 0)
                <div class="alert alert-warning">Nenhum inscrito nesse edital</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>    
                            <th scope="col">Número de Inscrição</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    @foreach ($inscritos as $inscrito)
                    <tr>
                        <th scope="row">{{ $inscrito->numeroInscricao }}</th>
                        <td>{{ $inscrito->name }}</td>
                        <td>{{ $inscrito->email }}</td>
                        <td><a href="/admin/inscrito/{{ $inscrito->codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm">Visualizar</a></td>
                    </tr>
                    @endforeach
                </table>
            @endif                        
        </div>
    </div>
</main>    
@endsection