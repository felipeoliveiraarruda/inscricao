@extends('layouts.app')

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">        
        <div class="col-sm-3">
            @include('admin.menu.list')           
        </div> 
        
        <div class="col-sm-9">
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
                                        $curso    = $utils->obterCurso($edital->codigoCurso);
                                        $semestre = App\Models\Edital::obterSemestreAno($edital->codigoEdital, true);
                                    @endphp

                                    <tr>
                                        <td>{{ $edital->descricaoNivel }} - {{ $semestre }} - {{ $curso['nomcur'] }}</td>
                                        <td>
                                            @if ($level == 'boss')
                                                <a href="inscricao/deferimento/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Inscritos">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            @else
                                                <a href="admin/listar-inscritos/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Inscritos">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                
                                                @if ($edital->codigoNivel == 1)
                                                    <!-- <a href="admin/deferimento/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Deferimento">
                                                        <i class="fas fa-list-ol"></i>
                                                    </a> -->

                                                    <a href="admin/lista-disciplina/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Inscritos por Disciplina">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </a>
                                                @else
                                                    <a href="admin/confirmados/{{ $edital->codigoEdital }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Confirmados">
                                                        <i class="fas fa-tasks"></i>
                                                    </a>
                                                @endif

                                                @if ($edital->codigoNivel == 6)
                                                    <a href="inscricao/{{ $edital->codigoEdital }}/proficiencia/download" target="_new" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Download">
                                                        <i class="fas fa-file-download"></i>
                                                    </a>
                                                    <a href="inscricao/{{ $edital->codigoEdital }}/proficiencia/lista" target="_new" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lista de Presença">
                                                        <i class="fas fa-list-ol"></i>
                                                    </a>
                                                @endif
                                                
                                                @if ($edital->codigoNivel == 4 && $edital->dataExameEdital > date('Y-m-d'))
                                                    <a href="inscricao/{{ $edital->codigoEdital }}/mestrado/lista" target="_new" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lista de Presença">
                                                        <i class="fas fa-list-ol"></i>
                                                    </a>
                                                @endif

                                                @if (($edital->codigoNivel == 4 || $edital->codigoNivel == 2) && date('Y-m-d') > $edital->dataExameEdital)
                                                    <a href="admin/{{ $edital->codigoEdital }}/aprovados" target="_new" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Aprovados">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </a>
                                                @endif

                                                @if ($pae == true && $edital->codigoNivel == 5)
                                                    <!--<a href="admin/{{ $edital->codigoEdital }}/pae/distribuicao">Distribuir Avaliação</a>-->

                                                    <a href="admin/{{ $edital->codigoEdital }}/pae/classificacao" role="button" aria-pressed="true" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Classificação">
                                                        <i class="fas fa-check-double"></i>
                                                    </a>
                                                @endif

                                                @if ($edital->codigoNivel == 5)
                                                    <a href="admin/{{ $edital->codigoEdital }}/pae/recurso" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Recursos">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </a>
                                                @endif

                                                @if ($edital->codigoNivel == 6 && $edital->dataExameEdital <= date('Y-m-d'))                                                
                                                    <a href="admin/{{ $edital->codigoEdital }}/exame" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Exame">
                                                        <i class="fas fa-book-open"></i>
                                                    </a>
                                                @endif
                                                
                                            @endif

                                        </td>       
                                     </tr>
                                @endforeach

                                @if($regulamentacao)
                                    @php
                                        $regulamentos = App\Models\Regulamento::all()
                                    @endphp

                                    @foreach ($regulamentos as $regulamento)
                                        <tr>                                        
                                            <td>{{ $regulamento->descricaoRegulamento }}</td>                                        
                                            <td>
                                                <a href="admin/{{ $regulamento->codigoRegulamento }}/regulamentacao" role="button" aria-pressed="true" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Inscritos">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            @endif
                        </tbody>
                    </table>               
                </div>
            </div>
        </div>
    </div>
</main>

@endsection