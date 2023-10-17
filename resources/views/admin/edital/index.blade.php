@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inscrições</a></li>
            <li class="breadcrumb-item" aria-current="page">Edital</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="/admin/edital/novo" class="list-group-item list-group-item-action">Novo Edital</a>
            </div>
        </div>      
        
        <div class="col-sm-9">
            <h4>Edital</h4>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div>

            @if (count($editais) == 0)
                <div class="alert alert-warning">Nenhum edital cadastrado</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>    
                            <th scope="col">#</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Nível</th>
                            <th scope="col">Início</th> 
                            <th scope="col">Final</th>
                            <!--<th scope="col">Ações</th>-->
                        </tr>
                    </thead>

                @foreach ($editais as $edital)
                    @php
                        $curso = $utils->obterCurso($edital->codigoCurso);
                    @endphp   
                    <tr>
                        <th scope="row">{{ $edital->codigoEdital }}</th>
                        <td><a href="{{ $edital->linkEdital }}" target="_new">{{ $curso['nomcur'] }}</a></td>
                        <td>{{ $utils->obterNivelEdital($edital->nivelEdital) }}</td>
                        <td>{{ $edital->dataInicioEdital->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $edital->dataFinalEdital->format('d/m/Y H:i:s') }}</td>
                        <!--<td>Editar - Ver</td>-->
                    </tr>
                @endforeach
                </table>
            @endif                        
        </div>
    </div>
</main>    
@endsection