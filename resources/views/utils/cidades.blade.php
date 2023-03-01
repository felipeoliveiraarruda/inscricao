<label for="naturalidadePessoal" class="font-weight-bold">{{ __('Cidade') }}<span class="text-danger">*</span></label>

@if($cidades != null)  
    <select class="form-control" id="cidadePessoal" name="cidadePessoal" required>
        <option value="">Selecione a cidade</option>
        @foreach($cidades as $cidade)
            <option value="{{ $cidade['cidloc'] }}">{{ $cidade['cidloc'] }}</option>
        @endforeach
    </select> 
@else
    <input type="text" class="form-control" id="cidadePessoal" name="cidadePessoal" value="{{ old('cidadePessoal') }}" required>
@endif