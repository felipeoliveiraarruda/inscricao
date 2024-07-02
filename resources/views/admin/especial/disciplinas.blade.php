@extends('layouts.app')

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">        
        <div class="col-sm-3">
            @include('admin.menu.list')           
        </div> 
        
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscritos por Disciplinas</h5>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">                               
                            @if (count($inscritos) > 0)
                            <div class="accordion" id="accordionArquivos">
                                @foreach($inscritos as $inscrito)
                                    @php
                                        $temp       = \Uspdev\Replicado\Posgraduacao::disciplina($inscrito->codigoDisciplina);
                                        $candidatos = \App\Models\Inscricao::select(\DB::raw('users.*, documentos.*, inscricoes.*'))
                                                                           ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                                                           ->join('inscricoes_disciplinas', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                                                           ->leftJoin('inscricoes_documentos', 'inscricoes.codigoInscricao', '=', 'inscricoes_documentos.codigoInscricao')
                                                                           ->leftJoin('documentos', 'inscricoes_documentos.codigoDocumento', '=', 'documentos.codigoDocumento')
                                                                           ->where('inscricoes.codigoEdital', $codigoEdital)
                                                                           ->where('inscricoes_disciplinas.codigoDisciplina', $inscrito->codigoDisciplina)
                                                                           ->orderBy('users.name')
                                                                           ->get();
                                    @endphp
                                <div class="card">
                                    <div class="card-header" id="heading{{ $inscrito->codigoDisciplina }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $inscrito->codigoDisciplina }}" aria-expanded="true" aria-controls="collapse{{ $inscrito->codigoDisciplina }}">
                                                {{ $temp['sgldis'] }}-{{ $temp['numseqdis'] }} {{ $temp['nomdis'] }}<span class="badge badge-pill badge-info float-right">{{ $inscrito->total }}</span>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse{{ $inscrito->codigoDisciplina }}" class="collapse" aria-labelledby="heading{{ $inscrito->codigoDisciplina }}" data-parent="#accordionArquivos">
                                        <div class="card-body">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nome</th>
                                                        <th scope="col">E-mail</th>
                                                        <th scope="col">Documento</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                </thead> 
                                                @foreach($candidatos as $candidato)
                                                <tr>
                                                    <td>{{ $candidato->name }}</td>
                                                    <td>{{ $candidato->email }}</td>
                                                    <td>{{ $candidato->numeroDocumento }}</td>
                                                    <td>
                                                        @if($candidato->statusInscricao == 'N')
                                                            Aberta
                                                        @elseif($candidato->statusInscricao == 'P')
                                                            Em Análise
                                                        @elseif($candidato->statusInscricao == 'C')
                                                            Confirmado
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>                                     
                                </div>
                                @endforeach
                            </div>
                            @endif 
                        </div>                
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection