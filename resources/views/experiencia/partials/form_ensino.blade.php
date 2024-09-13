<div class="form-group">  
    <label for="entidadeExperiencia" class="font-weight-bold">Entidade<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="entidadeExperiencia" name="entidadeExperiencia" value="{{ old('entidadeExperiencia') ?? $ensino->entidadeExperiencia ?? '' }}" maxlength="255" required />
</div>


<div class="form-group">  
    <label for="codigoTipoEntidade" class="font-weight-bold">Tipo de Entidade<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoTipoEntidade" name="codigoTipoEntidade" required>
        <option value="">Selecione uma opção</option>
        @foreach($tipos as $tipo)
            <option value="{{ $tipo->codigoTipoEntidade }}" {{ old('codigoTipoEntidade') == $tipo->codigoTipoEntidade ?? "selected" ?? $ensino->codigoTipoEntidade == $tipo->codigoTipoEntidade ?? "selected" ?? "" }}>{{ $tipo->tipoEntidade }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">  
    <label for="posicaoExperiencia" class="font-weight-bold">Posição Ocupada<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="posicaoExperiencia" name="posicaoExperiencia" value="{{ old('posicaoExperiencia') ?? $ensino->posicaoExperiencia ?? '' }}" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="inicioExperiencia" class="font-weight-bold">Início<span class="text-danger">*</span></label>
            @if (!empty($ensino->inicioExperiencia))
                <input type="date" step="1" class="form-control" id="inicioExperiencia" name="inicioExperiencia" value="{{ old('inicioExperiencia') ?? $ensino->inicioExperiencia->format('Y-m-d') ?? '' }}" required />
            @else
                <input type="date" step="1" class="form-control" id="inicioExperiencia" name="inicioExperiencia" value="{{ old('inicioExperiencia') ?? '' }}" />
            @endif    
        </div>

        <div class="col">
            <label for="finalExperiencia" class="font-weight-bold">Final</label>
            @if (!empty($ensino->finalExperiencia))
                <input type="date" step="1" class="form-control" id="finalExperiencia" name="finalExperiencia" value="{{ old('finalExperiencia') ?? $ensino->finalExperiencia->format('Y-m-d') ?? '' }}" />
            @else
                <input type="date" step="1" class="form-control" id="finalExperiencia" name="finalExperiencia" value="{{ old('finalExperiencia') ?? "" }}" />
            @endif
        </div> 
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoExperiencia" value="{{ $codigoExperiencia }}">
<input type="hidden" name="codigoInscricaoExperiencia" value="{{ $codigoInscricaoExperiencia }}">