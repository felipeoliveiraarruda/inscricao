<div class="form-group">
    <label for="justificativaRecurso" class="font-weight-bold">Recurso</label><span class="text-danger">*</span></label>
    <p class="text-justify">{{ $recurso->justificativaRecurso }}</p>
</div>

@if ($recurso->statusRecurso == 'N')
<div class="form-group">
    <label for="statusRecurso" class="font-weight-bold">Status</label><span class="text-danger">*</span></label><br/>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="statusRecurso[]" id="inlineStatusD" value="D" checked required>
        <label class="form-check-label" for="inlineRadio1">Deferido</label>
        </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="statusRecurso[]" id="inlineStatusI" value="I" required>
        <label class="form-check-label" for="inlineRadio2">Indeferido</label>
    </div>
</div>

<div class="form-group">
    <label for="analiseRecurso" class="font-weight-bold">Análise</label><span class="text-danger">*</span></label>
    <textarea class="form-control" id="analiseRecurso" name="analiseRecurso" rows="5" required>{{ $recurso->analiseRecurso }}</textarea>
</div>

<button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>

<input type="hidden" name="codigoRecurso" value="{{ $recurso->codigoRecurso }}">
<input type="hidden" name="codigoEdital" value="{{ $recurso->codigoEdital }}">

@else
<div class="form-group">
    <label for="statusRecurso" class="font-weight-bold">Status</label><span class="text-danger">*</span></label>
    <p class="text-justify">
        @if ($recurso->statusRecurso == 'N')
            Aberta
        @elseif ($recurso->statusRecurso == 'D')
            Deferido
        @else
            Indeferido
        @endif        
    </p>
</div>

<div class="form-group">
    <label for="analiseRecurso" class="font-weight-bold">Análise</label><span class="text-danger">*</span></label>
    <p class="text-justify">{{ $recurso->analiseRecurso }}</p>
</div>

<div class="form-group">
    <label for="analiseRecurso" class="font-weight-bold">Análise</label><span class="text-danger">*</span></label>
    <textarea class="form-control" id="analiseRecurso" name="analiseRecurso" rows="5" required>{{ $recurso->analiseRecurso }}</textarea>
</div>

<button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>

<input type="hidden" name="codigoRecurso" value="{{ $recurso->codigoRecurso }}">
<input type="hidden" name="codigoEdital" value="{{ $recurso->codigoEdital }}">
@endif  
