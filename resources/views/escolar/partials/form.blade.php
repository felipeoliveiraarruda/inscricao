<div class="form-group">  
    <label for="escolaResumoEscolar" class="font-weight-bold">Escola<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="escolaResumoEscolar" name="escolaResumoEscolar" value="{{ old('escolaResumoEscolar') ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <label for="especialidadeResumoEscolar" class="font-weight-bold">Título/Especialidade<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="especialidadeResumoEscolar" name="especialidadeResumoEscolar" value="{{ old('especialidadeResumoEscolar') ?? '' }}" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="tipoResumoEscolar" class="font-weight-bold">Tipo<span class="text-danger">*</span></label><br/>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoResumoEscolar" id="inicioResumoEscolarGraduacao" value="Graduação" checked/> 
                <label class="form-check-label" for="inicioResumoEscolarGraduacao">Graduação</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoResumoEscolar" id="inicioResumoEscolarMestrado" value="Mestrado"/>
                <label class="form-check-label" for="inicioResumoEscolarMestrado">Mestrado</label>
              </div>
        </div>

        <div class="col">
            <label for="finalResumoEscolar" class="font-weight-bold">Ano da Titulação<span class="text-danger">*</span></label>
            <input type="number" class="form-control" min="1900" max="2099" step="1" value="{{ old('finalResumoEscolar') ?? $ano }}" id="finalResumoEscolar" name="finalResumoEscolar"/>
        </div> 
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoResumoEscolar" value="{{ $codigoResumoEscolar }}">
<input type="hidden" name="codigoInscricaoResumoEscolar" value="{{ $codigoInscricaoResumoEscolar }}">
<input type="hidden" name="inicioResumoEscolar" value="NULL">