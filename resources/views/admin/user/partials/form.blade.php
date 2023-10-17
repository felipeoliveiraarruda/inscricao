<div class="form-group">                            
    <label for="name">{{ __('Name') }}</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
</div>

<div class="form-group">                            
    <label for="email">{{ __('Email') }}</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="cpf">{{ __('CPF') }}</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" required >
        </div>

        <div class="col">
            <label for="rg">{{ __('RG') }}</label>
            <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') }}">
        </div>

        <div class="col">
            <label for="telefone">{{ __('Telefone') }}</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('rg') }}" >
        </div> 
    </div>
</div> 


<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="col">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
        </div>
    </div>
</div>