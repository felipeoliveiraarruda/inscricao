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
                <h6 class="card-header">Cadastrar Senha</h6>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4 text-danger" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                        <!-- Email Address -->
                        <div class="form-group">                            
                            <label for="email">{{ __('Email') }}</label>
                            <p>{{ old('email', $request->email) }}</p>    
                        </div>

                        <div class="form-group">                            
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                        </div>

                        <div class="form-group">                            
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                        </div>                        

                        <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Reset Password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection