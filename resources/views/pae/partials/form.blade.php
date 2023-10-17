<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="participacaoPae" class="font-weight-bold">Já participou do PAE anteriormente? <span class="text-danger">*</span></label><br/>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="participacaoPae[]" id="participacaoPae1" value="S" required>
                <label class="form-check-label" for="participacaoPae1">Sim</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="participacaoPae[]" id="participacaoPae2" value="N" required>
                <label class="form-check-label" for="participacaoPae2">Não</label>
            </div>
        </div>
        <div class="col">
            <label for="tipoEspecialPessoal" class="font-weight-bold">Já recebeu remuneração do PAE? <span class="text-danger">*</span></label><br/>  
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="remuneracaoPae[]" id="remuneracaoPae1" value="S" required>
                <label class="form-check-label" for="remuneracaoPae1">Sim</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="remuneracaoPae[]" id="remuneracaoPae2" value="N" required>
                <label class="form-check-label" for="remuneracaoPae2">Não</label>
            </div>
        </div> 
    </div>
</div>

<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">