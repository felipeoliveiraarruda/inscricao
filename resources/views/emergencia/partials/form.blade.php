<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="name" class="font-weight-bold">{{ __('Name') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nomePessoaEmergencia" name="nomePessoaEmergencia" value="{{  old('nomePessoaEmergencia') ?? $emergencia->nomePessoaEmergencia ?? '' }}" required>
        </div>
        <div class="form-group">                            
            <label for="telefone" class="font-weight-bold">{{ __('Telefone') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="telefonePessoaEmergencia" name="telefonePessoaEmergencia" value="{{ old('telefonePessoaEmergencia') ?? $emergencia->telefonePessoaEmergencia ?? '' }}" required>
            <small id="telefoneAjudaBlock" class="form-text text-muted"></small> 
        </div>         
    </div>
</div> 


@if (empty($emergencia->codigoEmergenciaInscricao))
<div class="form-group">
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="mesmoEnderecoOptions[]" id="mesmoEndereco" value="S">
        <label class="form-check-label" for="mesmoEndereco">Usar o endereço já cadastrado</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="mesmoEnderecoOptions[]" id="novoEndereco" value="N">
        <label class="form-check-label" for="novoEndereco">Novo Endereço</label>
    </div>
</div>

<div id="mostrarEnderecoEmergencia">
    <div class="form-group">  
        <div class="form-row">
            <div class="col-2">  
                <label for="cep" class="font-weight-bold">CEP<span class="text-danger">*</span></label>
                <input type="text" id="cep" name="cep" value="{{ old('cep') ?? '' }}" class="form-control" maxlength="9">
            </div>
            <div class="col-8">  
                <label for="logradouro" class="font-weight-bold">Logradouro<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') ?? '' }}"  maxlength="255"> 
            </div>
            <div class="col-2">  
                <label for="numero" class="font-weight-bold">Número<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="numero" name="numero" value="{{ old('numero') ?? '' }}"  maxlength="5">
            </div>
        </div>   
    </div>
    
    <div class="form-group">  
        <div class="form-row">
            <div class="col">
                <label for="complemento" class="font-weight-bold">Complemento</label>
                <input class="form-control" type="text" id="complemento" name="complemento" value="{{ old('complemento') ?? '' }}" maxlength="255">
            </div>
            <div class="col">
                <label for="bairro" class="font-weight-bold">Bairro<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="bairro" name="bairro" value="{{ old('bairro') ?? '' }}" maxlength="255">
            </div>
            <div class="col">
                <label for="cidade" class="font-weight-bold">Cidade<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="localidade" name="localidade" value="{{ old('localidade') ?? '' }}" maxlength="255">
            </div>
            <div class="col">
                <label for="uf" class="font-weight-bold">UF<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="uf" name="uf" value="{{ old('uf') ?? '' }}" maxlength="2">
            </div>                        
        </div>              
    </div>
</div>
@else
    @if($emergencia->mesmoEndereco == 'S')
        <p>Mesmo endereço já cadastrado</p>
    @else
        <div class="form-group">  
            <div class="form-row">
                <div class="col-2">  
                    <label for="cep" class="font-weight-bold">CEP<span class="text-danger">*</span></label>
                    <input type="text" id="cep" name="cep" value="{{ old('cep') ?? $endereco->cepEndereco ?? '' }}" class="form-control" maxlength="9" required>
                </div>
                <div class="col-8">  
                    <label for="logradouro" class="font-weight-bold">Logradouro<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') ?? $endereco->logradouroEndereco ?? '' }}"  maxlength="255" required> 
                </div>
                <div class="col-2">  
                    <label for="numero" class="font-weight-bold">Número<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="numero" name="numero" value="{{ old('numero') ?? $endereco->numeroEndereco ?? '' }}"  maxlength="5" required>
                </div>
            </div>   
        </div>
        
        <div class="form-group">  
            <div class="form-row">
                <div class="col">
                    <label for="complemento" class="font-weight-bold">Complemento</label>
                    <input class="form-control" type="text" id="complemento" name="complemento" value="{{ old('complemento') ?? $endereco->complementoEndereco ?? '' }}" maxlength="255">
                </div>
                <div class="col">
                    <label for="bairro" class="font-weight-bold">Bairro<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="bairro" name="bairro" value="{{ old('bairro') ?? $endereco->bairroEndereco ?? '' }}" maxlength="255" required>
                </div>
                <div class="col">
                    <label for="cidade" class="font-weight-bold">Cidade<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="localidade" name="localidade" value="{{ old('localidade') ?? $endereco->localidadeEndereco ?? '' }}" maxlength="255" required>
                </div>
                <div class="col">
                    <label for="uf" class="font-weight-bold">UF<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="uf" name="uf" value="{{ old('uf') ?? $endereco->ufEndereco ?? '' }}" maxlength="2" required>
                </div>                        
            </div>              
        </div>
    @endif
@endif

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoInscricaoEndereco" value="{{ $codigoInscricaoEndereco}}">
<input type="hidden" name="codigoEndereco" value="{{ $endereco->codigoEndereco }}">
<input type="hidden" name="mesmoEnderecoOptions[]" value="{{ $emergencia->mesmoEndereco }}">