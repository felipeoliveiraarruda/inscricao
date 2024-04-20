<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="name" class="font-weight-bold">{{ __('Name') }} Completo<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nomePessoaEmergencia" name="nomePessoaEmergencia" value="{{  old('nomePessoaEmergencia') ?? $emergencia->nomePessoaEmergencia ?? '' }}" required>
        </div>
        <div class="form-group">                            
            <label for="telefone" class="font-weight-bold">{{ __('Telefone') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="telefonePessoaEmergencia" name="telefonePessoaEmergencia" value="{{ old('telefonePessoaEmergencia') ?? $emergencia->telefonePessoaEmergencia ?? '' }}" required>
            <small id="telefoneAjudaBlock" class="form-text text-muted"></small> 
        </div>         
    </div>
</div> 

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoInscricaoEndereco" value="{{ $codigoInscricaoEndereco}}">