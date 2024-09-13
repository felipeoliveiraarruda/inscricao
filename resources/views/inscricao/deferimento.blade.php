@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12">
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
        </div>

        <div class="col-sm-6">
            <div class="card bg-default">
                <h6 class="card-header">{{ $disciplina }}</h6>
                
                <div class="card-body">
                    @if(count($inscritos) > 0)
                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                        
                        <form class="needs-validation" novalidate method="POST" action="inscricao/deferimento">
                            @csrf
                            <div class="form-group">
                                <label for="expectativasInscricao" class="font-weight-bold">Selecione o(s) candidato(s) para o deferimento<span class="text-danger">*</span></label>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkTodosDeferimento" id="checkTodosDeferimento">
                                    <label class="form-check-label" for="checkTodosDeferimento">
                                        Selecionar todos
                                    </label>
                                </div>
                            
                                @foreach($inscritos as $inscrito)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="deferimentoCandidato[]" id="deferimentoCandidato{{ $inscrito->codigoInscricaoDisciplina }}" value="{{ $inscrito->codigoInscricaoDisciplina }}">
                                    <label class="form-check-label" for="deferimentoCandidato{{ $inscrito->codigoInscricaoDisciplina }}">
                                        {{ $inscrito->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">

                            <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card bg-default">
                <h6 class="card-header">Deferidos</h6>
                
                <div class="card-body">
                    @if(count($deferidos) > 0)
                        <table class="table">
                            <tbody>
                                @foreach($deferidos as $deferidos)
                                <tr>
                                    <td> {{ $deferidos->name }}</td>
                                    <td>
                                        <a href="inscricao/deferimento/destroy/{{ $deferidos->codigoInscricaoDisciplina }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desfazer">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>        
    </div>
</main>
@endsection