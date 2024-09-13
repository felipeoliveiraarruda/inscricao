<div class="form-group">  
    <label for="entidadeExperiencia" class="font-weight-bold">Entidade<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="entidadeExperiencia" name="entidadeExperiencia" value="{{ old('entidadeExperiencia') ?? $profissional->entidadeExperiencia ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <label for="posicaoExperiencia" class="font-weight-bold">Posição Ocupada<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="posicaoExperiencia" name="posicaoExperiencia" value="{{ old('posicaoExperiencia') ?? $profissional->posicaoExperiencia ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="inicioExperiencia" class="font-weight-bold">Início<span class="text-danger">*</span></label>
            @if (!empty($profissional->inicioExperiencia))
                <input type="date" step="1" class="form-control" id="inicioExperiencia" name="inicioExperiencia" value="{{ old('inicioExperiencia') ?? $profissional->inicioExperiencia->format('Y-m-d') ?? "" }}" required />
            @else
                <input type="date" step="1" class="form-control" id="inicioExperiencia" name="inicioExperiencia" value="{{ old('inicioExperiencia') ?? "" }}" />
            @endif    
        </div>

        <div class="col">
            <label for="finalExperiencia" class="font-weight-bold">Final</label>
            @if (!empty($profissional->finalExperiencia))
                <input type="date" step="1" class="form-control" id="finalExperiencia" name="finalExperiencia" value="{{ old('finalExperiencia') ?? $profissional->finalExperiencia->format('Y-m-d') ?? "" }}" />
            @else
                <input type="date" step="1" class="form-control" id="finalExperiencia" name="finalExperiencia" value="{{ old('finalExperiencia') ?? "" }}" />
            @endif
        </div> 
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoExperiencia" value="{{ $codigoExperiencia }}">
<input type="hidden" name="codigoInscricaoExperiencia" value="{{ $codigoInscricaoExperiencia }}">