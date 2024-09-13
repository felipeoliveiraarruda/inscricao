<div class="form-group">
    <label for="tipoVinculo" class="font-weight-bold">{{ __('Tipo de Vínculo') }}<span class="text-danger">*</span></label>
    <select class="form-control" id="tipoVinculo" name="tipoVinculo" required>
        <option value="">Selecione uma opção</option>
        <option value="CLT" {{ old('tipoVinculo') == 'CLT' ? "selected" : "" }}>CLT</option>
        <option value="Pessoa Jurídica" {{ old('tipoVinculo') == 'Pessoa Jurídica' ? "selected" : "" }}>Pessoa Jurídica</option>
        <option value="Regime Jurídico Único" {{ old('tipoVinculo') == 'Regime Jurídico Único' ? "selected" : "" }}>Regime Jurídico Único</option>
        <option value="Temporário Lei 6.019/74" {{ old('tipoVinculo') == 'Temporário Lei 6.019/74' ? "selected" : "" }}>Temporário Lei 6.019/74</option>
        <option value="Contrato por prazo determinado Lei 9.601/98" {{ old('tipoVinculo') == 'Contrato por prazo determinado Lei 9.601/98' ? "selected" : "" }}>Contrato por prazo determinado Lei 9.601/98</option>
    </select> 
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="inicioAtividade" class="font-weight-bold">{{ __('Início da Atividade') }}<span class="text-danger">*</span></label>
            <input type="date" step="1" class="form-control" id="inicioAtividade" name="inicioAtividade" value="" />
        </div>

        <div class="col">
            <label for="finalAtividade" class="font-weight-bold">{{ __('Fim da Atividade') }}<span class="text-danger">*</span></label>
            <input type="date" step="1" class="form-control" id="finalAtividade" name="finalAtividade" value="" />
        </div>
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="secaoCnae" class="font-weight-bold">{{ __('Seção CNAE') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="secaoCnae" name="secaoCnae" required>
                <option value="">Selecione uma opção</option>
                @foreach($secoes as $secao)
                    <option value="{{ $secao['id'] }}">{{ $secao['descricao'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <label for="divisaoCnae" class="font-weight-bold">{{ __('Divisão CNAE') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="divisaoCnae" name="divisaoCnae" required>
                <option value="">Selecione uma opção</option>
            </select>
        </div>
    </div>
</div> 