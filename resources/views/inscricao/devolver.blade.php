@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-3">     
            <div class="list-group">
                <a href="{{ url()->previous() }}" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>    

        <div class="col-md-9">   
            <div class="card bg-default">
                <h5 class="card-header">{{ $inscricao->numeroInscricao }} - {{ $inscricao->name }} - Devolver Inscrição</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                            @endif
                        @endforeach
                    </div>                    
                    
                    <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/devolver/{{ $codigoInscricao }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="body"><b>Justificativa</b><span class="text-danger">*</span></label>  
                            <textarea class="form-control" name="body" id="body" maxlenght="500" rows="15" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Devolver</button>
                    </form>

                    <!-- Modal -->
                    @include('utils.loader')
                </div>
            </div>
        </div>
    </div>
</main>
@endsection