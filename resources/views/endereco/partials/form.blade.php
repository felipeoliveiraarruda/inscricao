<div class="form-group">  
    <div class="form-row">
        <div class="col-2">  
            <label for="cep" class="font-weight-bold">CEP<span class="text-danger">*</span></label>
            <input type="text" id="cep" name="cep" value="{{ old('cep') ?? $enderecos->cepEndereco ?? '' }}" class="form-control" maxlength="9" required>
        </div>
        <div class="col-8">  
            <label for="logradouro" class="font-weight-bold">Logradouro<span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') ?? $enderecos->logradouroEndereco ?? '' }}"  maxlength="255" required> 
        </div>
        <div class="col-2">  
            <label for="numero" class="font-weight-bold">Número<span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="numero" name="numero" value="{{ old('numero') ?? $enderecos->numeroEndereco ?? '' }}"  maxlength="5" required>
        </div>
    </div>   
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="complemento" class="font-weight-bold">Complemento</label>
            <input class="form-control" type="text" id="complemento" name="complemento" value="{{ old('complemento') ?? $enderecos->complementoEndereco ?? '' }}" maxlength="255">
        </div>
        <div class="col">
            <label for="bairro" class="font-weight-bold">Bairro<span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="bairro" name="bairro" value="{{ old('bairro') ?? $enderecos->bairroEndereco ?? '' }}" maxlength="255" required>
        </div>
        <div class="col">
            <label for="cidade" class="font-weight-bold">Cidade<span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="localidade" name="localidade" value="{{ old('localidade') ?? $enderecos->localidadeEndereco ?? '' }}" maxlength="255" required>
        </div>
        <div class="col">
            <label for="uf" class="font-weight-bold">UF<span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="uf" name="uf" value="{{ old('uf') ?? $enderecos->ufEndereco ?? '' }}" maxlength="2" required>
        </div>                        
    </div>              
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">