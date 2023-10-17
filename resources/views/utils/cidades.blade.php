<label for="naturalidadePessoal" class="font-weight-bold">{{ __('Cidade') }}<span class="text-danger">*</span></label>

<select class="form-control" id="naturalidadePessoal" name="naturalidadePessoal" required>
    <option value="">Selecione a cidade</option>
    @foreach($cidades as $cidade)
        <option value="{{ $cidade['cidloc'] }}">{{ $cidade['cidloc'] }}</option>
    @endforeach
</select>