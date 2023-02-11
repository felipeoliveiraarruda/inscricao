<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="name" class="font-weight-bold">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>
        <div class="col">
            <label for="email" class="font-weight-bold">{{ __('Email') }}</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>   
        <div class="col">
            <label for="email" class="font-weight-bold">{{ __('Data de Nascimento') }}</label>
            <input type="date" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>          
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="cpf" class="font-weight-bold">{{ __('CPF') }}</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" required disabled>
        </div>
        <div class="col">
            <label for="rg" class="font-weight-bold">{{ __('RG') }}</label>
            <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}" required>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Data de Emissão</label>
            <input type="date" class="form-control" id="dataEmissaoRG" name="dataEmissaoRG" value="{{ old('dataEmissaoRG') }}" required>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Orgão Emissor</label>
            <input type="text" class="form-control" id="dataEmissaoRG" name="dataEmissaoRG" value="{{ old('dataEmissaoRG') }}" required>
        </div>  
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="cpf" class="font-weight-bold">Sexo</label>
            
        </div>
        <div class="col">
            <label for="rg" class="font-weight-bold">Raça/Cor</label>

        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Estado Civil</label>
            
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Número de Dependentes</label>

        </div>  
    </div>
</div>

   

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">