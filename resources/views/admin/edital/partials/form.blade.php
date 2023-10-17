
<div class="form-group">
    <label for="codigoCurso">Curso<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoCurso" name="codigoCurso" required>
        <option value="">Selecione o Curso</option>
        @foreach ($cursos as $curso)
            <option value="{{ $curso['codcur'] }}" {{ old('codigoCurso') == $edital->codigoCurso ? "selected" : $curso['codcur'] == $edital->codigoCurso ? "selected" : "" }}>{{ $curso['nomcur'] }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="codigoNivel">Nível<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoNivel" name="codigoNivel" required>        
        <option value="">Selecione o Nível</option>
        @foreach ($niveis as $nivel)
            <option value="{{ $nivel->codigoNivel }}" {{ old('codigoNivel') == $edital->codigoNivel ? "selected" : $nivel->codigoNivel == $edital->codigoNivel ? "selected" : "" }}>{{ $edital->descricaoNivel }}</option>
        @endforeach
    </select>
</div> 

<div class="form-group">  
    <label for="linkEdital">Link<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="linkEdital" name="linkEdital" maxlength="255" required value="{{ old('linkEdital') ?  $edital->linkEdital : "" }}"/>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="dataDoeEdital">Data DOE</label>
            <input type="datetime-local" step="1" class="form-control" id="dataDoeEdital" name="dataDoeEdital" value="{{ old('dataDoeEdital') ?  $edital->dataDoeEdital : "" }}" />
        </div>

        <div class="col">
            <label for="dataInicioEdital">Início<span class="text-danger">*</span></label>
            <input type="datetime-local" step="1" class="form-control" id="dataInicioEdital" name="dataInicioEdital" value="{{ old('dataInicioEdital') ?  $edital->dataInicioEdital : "" }}" required />
        </div>

        <div class="col">
            <label for="dataFinalEdital">Final<span class="text-danger">*</span></label>
            <input type="datetime-local" step="1" class="form-control" id="dataFinalEdital" name="dataFinalEdital" value="{{ old('dataFinalEdital') ?  $edital->dataFinalEdital : "" }}" required />
        </div> 
    </div>
</div> 

<div class="form-group">  
    <label for="codigoTipoDocumento">Documentos</label>
    <select multiple class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento[]" required>                
        @foreach ($tipos as $tipo)
            <option value="{{ $tipo->codigoTipoDocumento }}" {{ old('codigoTipoDocumento') == $edital->codigoNivel ? "selected" : $tipo->codigoTipoDocumento == $edital->codigoTipoDocumento ? "selected" : "" }}>{{ $tipo->tipoDocumento }}</option>
        @endforeach
    </select>
</div>