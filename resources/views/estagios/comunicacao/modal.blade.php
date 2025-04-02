<div class="modal fade" id="addIdioma" tabindex="-1" aria-labelledby="addAutorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIdiomaLabel">Idioma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">  
                    <label for="descricaoIdioma" class="font-weight-bold">Idioma<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="descricaoIdioma" name="descricaoIdioma" maxlength="255" value="{{ old('descricaoIdioma') }}" data-idioma='true'/>
                </div>
                
                <div class="form-group">   
                    <label for="leituraIdioma" class="font-weight-bold">Leitura<span class="text-danger">*</span></label>
                    <select class="form-control" id="leituraIdioma" name="leituraIdioma" data-idioma='true'>
                        <option value="">Selecione uma opção</option>
                        @foreach($idiomas as $temp)
                            <option value="{{ $temp }}" {{ old('leituraIdioma') == $temp ? "selected" : "" }}>{{ $temp }}</option>
                        @endforeach
                    </select>
                </div>  

                <div class="form-group">   
                    <label for="redacaoIdioma" class="font-weight-bold">Redação<span class="text-danger">*</span></label>
                    <select class="form-control" id="redacaoIdioma" name="redacaoIdioma" data-idioma='true'>
                        <option value="">Selecione uma opção</option>
                        @foreach($idiomas as $temp)
                            <option value="{{ $temp }}" {{ old('redacaoIdioma') == $temp ? "selected" : "" }}>{{ $temp }}</option>
                        @endforeach
                    </select>
                </div>  

                <div class="form-group">   
                    <label for="conversacaoIdioma" class="font-weight-bold">Conversação<span class="text-danger">*</span></label>
                    <select class="form-control" id="conversacaoIdioma" name="conversacaoIdioma" data-idioma='true'>
                        <option value="">Selecione uma opção</option>
                        @foreach($idiomas as $temp)
                            <option value="{{ $temp }}" {{ old('conversacaoIdioma') == $temp ? "selected" : "" }}>{{ $temp }}</option>
                        @endforeach
                    </select>
                </div> 
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="save-idioma">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>