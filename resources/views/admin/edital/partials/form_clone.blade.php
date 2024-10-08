
<div class="form-group">
    <label for="codigoCurso">Curso<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoCurso" name="codigoCurso" required>
        <option value="">Selecione o Curso</option>
        @foreach ($cursos as $curso)
            <option value="{{ $curso['codcur'] }}">{{ $curso['nomcur'] }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="codigoNivel">Nível<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoNivel" name="codigoNivel" required>        
        <option value="">Selecione o Nível</option>
        @foreach ($niveis as $nivel)
            <option value="{{ $nivel->codigoNivel }}">{{ $nivel->descricaoNivel }}</option>
        @endforeach
    </select>
</div> 

<div class="form-group">  
    <label for="linkEdital">Link<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="linkEdital" name="linkEdital" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="dataDoeEdital">Data DOE</label>
            <input type="datetime-local" step="1" class="form-control" id="dataDoeEdital" name="dataDoeEdital" value="" />
        </div>

        <div class="col">
            <label for="dataInicioEdital">Início<span class="text-danger">*</span></label>
            <input type="datetime-local" step="1" class="form-control" id="dataInicioEdital" name="dataInicioEdital" value="" required />
        </div>

        <div class="col">
            <label for="dataFinalEdital">Final<span class="text-danger">*</span></label>
            <input type="datetime-local" step="1" class="form-control" id="dataFinalEdital" name="dataFinalEdital" value="" required />
        </div> 
    </div>
</div> 

<div class="form-group">  
    <label for="codigoTipoDocumento">Documentos</label>
    <select multiple class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento[]" required>                
        @foreach ($tipos as $tipo)
            <option value="{{ $tipo->codigoTipoDocumento }}">{{ $tipo->tipoDocumento }}</option>
        @endforeach
    </select>
</div>