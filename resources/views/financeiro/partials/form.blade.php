<div class="form-group">  
    <label for="bolsaRecursoFinanceiro" class="font-weight-bold">Possui bolsa de estudos de alguma instituição?<span class="text-danger">*</span></label>
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineBolsa" id="inlineBolsaSim" value="S">
            <label class="form-check-label" for="inlineBolsaSim">Sim</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineBolsa" id="inlineBolsaNao" value="N" checked>
            <label class="form-check-label" for="inlineBolsaNao">Não</label>
        </div>
    </div>
</div>

<div id="solicitarBolsa">
    <div class="form-group">  
        <label for="solicitarRecursoFinanceiro" class="font-weight-bold">Deseja solicitar bolsa?<span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineSolicitar" id="inlineSolicitarSim" value="S">
            <label class="form-check-label" for="inlineSolicitarSim">Sim</label>
          </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineSolicitar" id="inlineSolicitarNao" value="N" checked>
            <label class="form-check-label" for="inlineSolicitarNao">Não</label>
        </div>
    </div>
</div>

<div id="dadosBolsa">
    <div class="form-group">  
        <label for="orgaoRecursoFinanceiro" class="font-weight-bold">Órgão Financiador<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="orgaoRecursoFinanceiro" name="orgaoRecursoFinanceiro" value="{{ old('orgaoRecursoFinanceiro') ?? $financeiro->orgaoRecursoFinanceiro ?? '' }}" maxlength="255" />
    </div>

    <div class="form-group">  
        <div class="form-row">
            <div class="col">
                <label for="inicioRecursoFinanceiro" class="font-weight-bold">Início<span class="text-danger">*</span></label>
                <input type="date" step="1" class="form-control" id="inicioRecursoFinanceiro" name="inicioRecursoFinanceiro" value="{{ old('inicioRecursoFinanceiro') ?? $financeiro->inicioRecursoFinanceiro ?? '' }}" />
            </div>
    
            <div class="col">
                <label for="finalRecursoFinanceiro" class="font-weight-bold">Final<span class="text-danger">*</span></label>
                <input type="date" step="1" class="form-control" id="finalRecursoFinanceiro" name="finalRecursoFinanceiro" value="{{ old('finalRecursoFinanceiro') ?? $financeiro->finalRecursoFinanceiro ?? '' }}" />
            </div> 
        </div>
    </div>

</div>




<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">