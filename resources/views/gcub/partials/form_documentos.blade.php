<div class="form-group">
    <label for="codigoTipoDocumento" class="font-weight-bold">Tipo<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento" required>
        <option value="">Selecione o Tipo de Documento</option>
        @foreach($tipos as $temp)
            <option value="{{ $temp->codigoTipoDocumento }}">{{ $temp->tipoDocumento }}</option>
        @endforeach
    </select>
</div>                           

<div class="form-group">
    <input type="file" class="form-control-file" id="arquivo" name="arquivo" required accept="application/pdf">
</div>

<input type="hidden" name="codigoGcub" value="{{ $codigoGcub }}">
<input type="hidden" name="tipo" value="{{ $tipo }}">
