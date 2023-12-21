

<div class="form-group">
    @foreach($tipos as $temp)
    <div class="row">
        @if($temp->codigoTipoDocumento == 1 || $temp->codigoTipoDocumento == 2 || $temp->codigoTipoDocumento == 3 || $temp->codigoTipoDocumento == 29 || $temp->codigoTipoDocumento == 30)
        <div class="col-4">
            <label for="codigoTipoDocumento" class="font-weight-bold">{{ $temp->tipoDocumento }}</label>
        </div>
        <div class="col">            
            <input type="file" class="form-control-file" id="arquivo" name="arquivo[{{ $temp->codigoTipoDocumento }}]" accept="application/pdf">            
        </div>
        @else
        <div class="col-4">
            <label for="codigoTipoDocumento" class="font-weight-bold">{{ $temp->tipoDocumento }}<span class="text-danger">*</span></label>
        </div>
        <div class="col">            
            <input type="file" class="form-control-file" id="arquivo" name="arquivo[{{ $temp->codigoTipoDocumento }}]" required accept="application/pdf">
        </div>        

        @endif
    </div>                
    @endforeach    
</div>


<input type="hidden" name="codigoGcub" value="{{ $codigoGcub }}">
<input type="hidden" name="tipo" value="{{ $tipo }}">
