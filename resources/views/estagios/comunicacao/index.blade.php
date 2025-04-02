@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action">Home</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Processo Seletivo Estágiario Comunicação 2025</h5>
                
                <div class="card-body">
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
                    <div class="row justify-content-center">
                        <div class="col-6 text-center">
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                            <form class="needs-validation" novalidate method="POST" action="estagios/verificacao">
                                @csrf
                                
                                <div class="form-group">                            
                                    <label for="cpfPessoal" class="font-weight-bold">{{ __('CPF') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cpfPessoal" name="cpfPessoal" value="{{ old('cpfPessoal') }}" required autofocus maxlength="11">
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Continuar</button>
                            </form>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

