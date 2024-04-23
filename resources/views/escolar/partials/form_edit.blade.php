<div class="form-group">  
    <label for="escolaResumoEscolar" class="font-weight-bold">Escola<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="escolaResumoEscolar" name="escolaResumoEscolar" value="{{ old('escolaResumoEscolar') ?? $escolar->escolaResumoEscolar ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <label for="especialidadeResumoEscolar" class="font-weight-bold">Título/Especialidade<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="especialidadeResumoEscolar" name="especialidadeResumoEscolar" value="{{ old('especialidadeResumoEscolar') ?? $escolar->especialidadeResumoEscolar ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="inicioResumoEscolar" class="font-weight-bold">Início<span class="text-danger">*</span></label>
            @if (!empty($escolar->inicioResumoEscolar))
            <input type="date" step="1" class="form-control" id="inicioResumoEscolar" name="inicioResumoEscolar" value="{{ old('inicioResumoEscolar') ?? $escolar->inicioResumoEscolar->format('Y-m-d') ?? "" }}" required />
            @else
                <input type="date" step="1" class="form-control" id="inicioResumoEscolar" name="inicioResumoEscolar" value="{{ old('inicioResumoEscolar') ?? "" }}" />
            @endif 
        </div>

        <div class="col">
            <label for="finalResumoEscolar" class="font-weight-bold">Final</label>
            @if (!empty($escolar->finalResumoEscolar))
            <input type="date" step="1" class="form-control" id="finalResumoEscolar" name="finalResumoEscolar" value="{{ old('finalResumoEscolar') ?? $escolar->finalResumoEscolar->format('Y-m-d') ?? "" }}" required />
            @else
                <input type="date" step="1" class="form-control" id="finalResumoEscolar" name="finalResumoEscolar" value="{{ old('finalResumoEscolar') ?? "" }}" />
            @endif
        </div> 
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoInscricaoResumoEscolar" value="{{ $codigoInscricaoResumoEscolar }}">
<input type="hidden" name="codigoResumoEscolar" value="{{ $codigoResumoEscolar }}">