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
                <h5 class="card-header">Entrar</h5>
                
                <div class="card-body">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />                 

                    <form method="POST" action="{{ route('login2') }}">
                        @csrf                       
                        <div class="form-group">                            
                            <label for="email" class="font-weight-bold">{{ __('Email') }}</label>
                            <input type="email" class="form-control" name="email" value="" required autofocus>
                        </div>

                        <div class="form-group">                            
                            <label for="password" class="font-weight-bold">{{ __('Password') }}</label>
                            <input type="password" class="form-control" name="password" value="" required>
                        </div>
                                                                    
                        <div class="flex items-center text-center mt-4">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div><br/>

                        <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Log in') }}</button>

                        <div class="flex items-center text-center mt-4">
                            Ainda não tem uma conta?<br/>
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                                Clique aqui para criar uma conta nova
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection