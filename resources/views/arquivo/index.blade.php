@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Documentos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/documento/novo" class="list-group-item list-group-item-action">Novo Documento</a>
            </div>
        </div>

        <div class="col-sm-9">
            <h4>Documento</h4>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>
            
            @if (count($arquivos) == 0)
                <div class="alert alert-warning">Nenhum documento cadastrado</div>
            @else

            @endif                            
        </div>
    </div>
</main>

@endsection