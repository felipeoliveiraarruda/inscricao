@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-3">     

        </div>    
        <div class="col-md-9">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Enviar E-mail</h5>
                
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
                    
                    <form class="needs-validation" novalidate method="POST" action="admin/enviar-email" id="formEmail">
                        @csrf

                        <div class="form-group">
                            <label for="assunto">Inscritos<span class="text-danger">*</span></label><br/>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDestinatario[]" id="tipoDestinatarioT" value="T" checked>
                                <label class="form-check-label" for="tipoDestinatarioT">Todos</label>
                            </div>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDestinatario[]" id="tipoDestinatarioN" value="N">
                                <label class="form-check-label" for="tipoDestinatarioN">Aberta</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDestinatario[]" id="tipoDestinatarioP" value="P">
                                <label class="form-check-label" for="tipoDestinatarioP">Pendente</label>
                            </div>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDestinatario[]" id="tipoDestinatarioC" value="C">
                                <label class="form-check-label" for="tipoDestinatarioC">Confirmada</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="assunto">Assunto<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="assunto" name="assunto" value="{{ old('assunto') }}" maxlength="255" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="body">Texto do E-mail<span class="text-danger">*</span></label>  
                            <textarea class="form-control" name="body" id="body" maxlenght="500" rows="15" required></textarea>
                        </div>

                        <input type="hidden" name="codigoEdital" value="{{ $id }}">

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                        <a href="admin/listar-inscritos/{{ $id }}" role="button" aria-pressed="true" class="btn btn-info btn-lg btn-block">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection