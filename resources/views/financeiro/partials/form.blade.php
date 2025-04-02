<div class="form-group">  
    <label for="bolsaRecursoFinanceiro" class="font-weight-bold">Possui bolsa de estudos de alguma instituição?<span class="text-danger">*</span></label>
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineBolsa" id="inlineBolsaSim" value="S" {{ (old('inlineBolsa') == 'S' ? 'checked' : '') }} >
            <label class="form-check-label" for="inlineBolsaSim">Sim</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineBolsa" id="inlineBolsaNao" value="N"{{ (old('inlineBolsa') == 'N' ? 'checked' : '') }}>
            <label class="form-check-label" for="inlineBolsaNao">Não</label>
        </div>
    </div>
</div>

<div id="dadosBolsa">
    <div class="form-group">  
        <label for="orgaoRecursoFinanceiro" class="font-weight-bold">Órgão Financiador<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="orgaoRecursoFinanceiro" name="orgaoRecursoFinanceiro" value="{{ old('orgaoRecursoFinanceiro') }}" maxlength="255" />
    </div>

    <div class="form-group">  
        <label for="tipoBolsaFinanceiro" class="font-weight-bold">Tipo de Bolsa<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="tipoBolsaFinanceiro" name="tipoBolsaFinanceiro" value="{{ old('tipoBolsaFinanceiro') }}" maxlength="255" />
    </div>

    <div class="form-group">  
        <div class="form-row">
            <div class="col">
                <label for="inicioRecursoFinanceiro" class="font-weight-bold">Início<span class="text-danger">*</span></label>
                <input type="date" step="1" class="form-control" id="inicioRecursoFinanceiro" name="inicioRecursoFinanceiro" value="{{ old('inicioRecursoFinanceiro') }}" />
            </div>
    
            <div class="col">
                <label for="finalRecursoFinanceiro" class="font-weight-bold">Final<span class="text-danger">*</span></label>
                <input type="date" step="1" class="form-control" id="finalRecursoFinanceiro" name="finalRecursoFinanceiro" value="{{ old('finalRecursoFinanceiro') }}" />
            </div> 
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
            <input class="form-check-input" type="radio" name="inlineSolicitar" id="inlineSolicitarNao" value="N">
            <label class="form-check-label" for="inlineSolicitarNao">Não</label>
        </div>
    </div>

    <div id="solicitarBolsaDados">
        <div class="form-group">  
            <div class="form-row">
                <div class="col-3">
                    <label for="anoTitulacaoRecursoFinanceiro" class="font-weight-bold">Ano da última titulação<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="anoTitulacaoRecursoFinanceiro" name="anoTitulacaoRecursoFinanceiro" value="{{ old('anoTitulacaoRecursoFinanceiro') }}" maxlength="4"/>
                </div>

                <div class="col-9">
                    <label for="iesTitulacaoRecursoFinanceiro" class="font-weight-bold">IES da última titulação<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="iesTitulacaoRecursoFinanceiro" name="iesTitulacaoRecursoFinanceiro" value="{{ old('iesTitulacaoRecursoFinanceiro') }}" maxlength="255" />
                </div>
            </div>
        </div>
        <div class="form-group">  
            <div class="form-row">

                <div class="col">
                    <label for="localRecursoFinanceiro" class="font-weight-bold">Cidade/UF da Agência</label>
                    <input type="text" class="form-control" id="localRecursoFinanceiro" name="localRecursoFinanceiro" value="{{ old('localRecursoFinanceiro') }}" maxlength="255" />
                </div>
                
                <div class="col-2">
                    <label for="agenciaRecursoFinanceiro" class="font-weight-bold">Agência</label>
                    <input type="text" class="form-control" id="agenciaRecursoFinanceiro" name="agenciaRecursoFinanceiro" value="{{ old('agenciaRecursoFinanceiro') }}"  maxlength="10"  />
                </div>

                <div class="col">
                    <label for="contaRecursoFinanceiro" class="font-weight-bold">Conta Corrente</label>
                    <input type="text" class="form-control" id="contaRecursoFinanceiro" name="contaRecursoFinanceiro" value="{{ old('contaRecursoFinanceiro') }}" maxlength="20" />
                </div>
            </div>
            <small id="agenciaRecursoFinanceiroHelp" class="form-text text-muted">A conta deve ser, obrigatoriamente, do Banco do Brasil e em seu Nome e CPF</small>
        </div>
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoRecursoFinanceiro" value="">