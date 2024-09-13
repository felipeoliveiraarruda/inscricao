@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item" aria-current="page">Tipo de Documento</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/admin/tipo-documento/novo" class="list-group-item list-group-item-action">Novo Tipo de Documento</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Tipo de Documento</h4>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>

            @if (count($tipos) == 0)
                <div class="alert alert-warning">Nenhum tipo de documento cadastrado</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>    
                            <th scope="col">#</th>
                            <th scope="col">Tipo de Documento</th>
                            <!--<th scope="col">Ações</th>-->
                        </tr>
                    </thead>

                @foreach ($tipos as $tipo)
                    <tr>
                        <th scope="row">{{ $tipo->codigoTipoDocumento }}</th>
                        <td>{{ $tipo->tipoDocumento }}</td>
                        <!--<td>Editar - Ver</td>-->
                    </tr>
                @endforeach
                </table>
            @endif                        
        </div>
    </div>
</main>    
@endsection