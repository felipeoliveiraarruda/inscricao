@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Registrar</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div id="exibirCampoCpf">
                            <div class="form-group">                            
                                <label for="cpfVerificar" class="font-weight-bold">{{ __('CPF') }}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cpfVerificar" name="cpfVerificar" value="{{ old('cpVerificar') }}" required autofocus>
                            </div>

                            <button id="btnProsseguir" type="button" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Prosseguir</button>
                        </div>


                        <div id="exibirCampos" class="mt-4">
                            <div class="form-group">                            
                                <label for="name" class="font-weight-bold">{{ __('Name') }} completo<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                    
                            <div class="form-group">                            
                                <label for="email" class="font-weight-bold">{{ __('Email') }}<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group">                            
                                <label for="rg" class="font-weight-bold">{{ __('RG') }}</label>
                                <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') }}" required>
                            </div>

                            <div class="form-group">                            
                                <label for="telefone" class="font-weight-bold">{{ __('Telefone') }}</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}"  required>
                            </div>

                            <div class="form-group">                            
                                <label for="password" class="font-weight-bold">{{ __('Password') }}<span class="text-danger">*</span></label>
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                            </div>

                            <div class="form-group">                            
                                <label for="password_confirmation" class="font-weight-bold">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                            </div>

                            <input type="hidden" id="cpf" name="cpf" value="">
                                                                    
                            <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Register') }}</button>

                            <div class="flex items-center mt-4 text-center">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="login">
                                    {{ __('Already registered?') }}
                                </a>
                            </div>
                        </div>                      
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection