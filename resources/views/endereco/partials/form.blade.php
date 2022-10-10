<div class="form-group">
    <label for="cep">CEP<span class="text-danger">*</span></label>
    <input type="text" id="cep" name="cep" value="{{ old('cep') }}" class="form-control" maxlength="9" required>
</div>

<div class="form-group">
    <label for="logradouro">Logradouro<span class="text-danger">*</span></label>
    <input class="form-control" type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') }}"  maxlength="255" required> 
</div>

<div class="form-group">
    <label for="numero">Número<span class="text-danger">*</span></label>
    <input class="form-control" type="text" id="numero" name="numero" value="{{ old('numero') }}"  maxlength="5" required>
</div>

<div class="form-group">
    <label for="complemento">Complemento</label>
    <input class="form-control" type="text" id="complemento" name="complemento" value="{{ old('complemento') }}" maxlength="255">
</div>

<div class="form-group">
    <label for="bairro">Bairro</label>
    <input class="form-control" type="text" id="bairro" name="bairro" value="{{ old('bairro') }}" maxlength="255">
</div>

<div class="form-group">
    <label for="cidade">Cidade</label>
    <input class="form-control" type="text" id="localidade" name="localidade" value="{{ old('localidade') }}" maxlength="255">
</div>

<div class="form-group">
    <label for="uf">UF</label>
    <input class="form-control" type="text" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2">
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">