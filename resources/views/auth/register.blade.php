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
                        <div class="form-group">                            
                            <label for="cpf">{{ __('CPF') }}</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" required autofocus>
                        </div>

                        <div class="form-group">                            
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                
                        <div class="form-group">                            
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">                            
                            <label for="rg">{{ __('RG') }}</label>
                            <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') }}" required>
                        </div>

                        <div class="form-group">                            
                            <label for="telefone">{{ __('Telefone') }}</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('rg') }}"  required>
                        </div>

                        <div class="form-group">                            
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                        </div>

                        <div class="form-group">                            
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                        </div>
                                                                
                        <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Register') }}</button>

                        <div class="flex items-center mt-4 text-center">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="acesso">
                                {{ __('Already registered?') }}
                            </a>
                        </div>
                        <hr>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection