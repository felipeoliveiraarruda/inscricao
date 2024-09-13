@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <br/>        
            <div class="card bg-default">
                <h6 class="card-header">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</h6>
                
                <div class="card-body">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4 text-danger" :errors="$errors" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">                            
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Email Password Reset Link') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection