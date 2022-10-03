@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">
        <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Dados Pessoais</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('dados') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group">                            
                            <label for="cpf">{{ __('CPF') }}</label>
                            <input type="text" class="form-control" id="cpf" name="cpf"
                            @if (Auth::user()->cpf != "99999999999")
                                value="{{ Auth::user()->cpf }}" disabled
                            @else                                
                                autofocus
                            @endif>
                        </div>

                        <div class="form-group">                            
                            <label for="rg">{{ __('RG') }}</label>
                            <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}"  required>
                        </div>

                        <div class="form-group">                            
                            <label for="telefone">{{ __('Telefone') }}</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="{{ Auth::user()->telefone }}"  required>
                        </div>                           

                        <div class="form-group">                            
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                
                        <div class="form-group">                            
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" disabled>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">{{ __('Update') }}</button>
                    </form>

                    <script>
                        // Example starter JavaScript for disabling form submissions if there are invalid fields
                        (function() {
                        'use strict';
                        window.addEventListener('load', function() {
                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                            var forms = document.getElementsByClassName('needs-validation');
                            // Loop over them and prevent submission
                            var validation = Array.prototype.filter.call(forms, function(form) {
                            form.addEventListener('submit', function(event) {
                                if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                            });
                        }, false);
                        })();
                    </script>                    
                </div>
            </div>
        </div>
        </div>
    </div>
</main>

@endsection