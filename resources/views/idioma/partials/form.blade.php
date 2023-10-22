<div class="form-group">  
    <label for="descricaoIdioma" class="font-weight-bold">Idioma<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="descricaoIdioma" name="descricaoIdioma" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">  
            <label for="leituraIdioma" class="font-weight-bold">Leitura<span class="text-danger">*</span></label>
            <select class="form-control" id="leituraIdioma" name="leituraIdioma" required>
                <option value="">Selecione uma opção</option>
                @foreach($idiomas as $idioma)
                    <option value="{{ $idioma }}" {{ old('leituraIdioma') }}>{{ $idioma }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <label for="redacaoIdioma" class="font-weight-bold">Redação<span class="text-danger">*</span></label>
            <select class="form-control" id="redacaoIdioma" name="redacaoIdioma" required>
                <option value="">Selecione uma opção</option>
                @foreach($idiomas as $idioma)
                    <option value="{{ $idioma }}" {{ old('leituraIdioma') }}>{{ $idioma }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <label for="conversacaoIdioma" class="font-weight-bold">Conversação<span class="text-danger">*</span></label>
            <select class="form-control" id="conversacaoIdioma" name="conversacaoIdioma" required>
                <option value="">Selecione uma opção</option>
                @foreach($idiomas as $idioma)
                    <option value="{{ $idioma }}" {{ old('leituraIdioma') }}>{{ $idioma }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">