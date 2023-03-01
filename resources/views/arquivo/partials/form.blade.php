<div class="form-group">
    <label for="codigoTipoDocumento">Tipo<span class="text-danger">*</span></label>
    <select class="form-control" id="codigoTipoDocumento" name="codigoTipoDocumento" required>
        <option value="">Selecione o Tipo de Documento</option>
        @foreach($tipos as $tipo)
            @if ($arquivo->codigoTipoDocumento == $tipo->codigoTipoDocumento)
                <option value="{{ $tipo->codigoTipoDocumento }}" selected>{{ $tipo->tipoDocumento }}</option>
            @else
                <option value="{{ $tipo->codigoTipoDocumento }}">{{ $tipo->tipoDocumento }}</option>
            @endif
        @endforeach
    </select>
</div>                           

@if ($arquivo->codigoTipoDocumento > 0)
    <div class="form-group">
        Arquivo Atual : <a href="storage/{{ $arquivo->linkArquivo }}" target="_new">Visualizar</a>
        <input type="hidden" name="arquivoAtual" value="{{ $arquivo->linkArquivo }}">
    </div>
@endif    

<div class="form-group">
    <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="linkVoltar" value="{{ $link_voltar }}">