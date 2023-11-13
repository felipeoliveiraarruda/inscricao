@extends('layouts.app')

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">        
        <div class="col-md-3">
            @include('admin.menu.list')           
        </div> 
        
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Administração</h5>
                
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
                        <tbody>
                            @if (count($editais) == 0)
                                Nenhuma inscrição aberta
                            @else
                                @foreach ($editais as $edital)
                                    @php
                                        $curso = $utils->obterCurso($edital->codigoCurso);
                                    @endphp       
                                    <tr>
                                        <td>{{ $edital->descricaoNivel }} - {{ $curso['nomcur'] }}</td>
                                        <td><a href="admin/listar-inscritos/{{ $edital->codigoEdital }}">Lista de Inscritos</a></td>
                                        @if ($pae == true)
                                        <td><!--<a href="admin/{{ $edital->codigoEdital }}/pae/distribuicao">Distribuir Avaliação</a>--></td>
                                        <td><a href="admin/{{ $edital->codigoEdital }}/pae/classificacao">Classificação</a></td>
                                        @endif
                                     </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>               
                </div>
            </div>
        </div>
    </div>
</main>

@endsection