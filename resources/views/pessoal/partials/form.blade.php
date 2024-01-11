<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="name" class="font-weight-bold">{{ __('Name') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{  old('name') ?? Auth::user()->name }}" required>
        </div>
        <div class="col">
            <label for="email" class="font-weight-bold">{{ __('Email') }}<span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? Auth::user()->email }}" required>
        </div>   
        <div class="col">                            
            <label for="telefone" class="font-weight-bold">{{ __('Telefone') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') ?? $pessoais->telefone ?? ''}}" required>
        </div>
        <div class="col">
            <label for="dataNascimentoPessoal" class="font-weight-bold">{{ __('Data de Nascimento') }}<span class="text-danger">*</span></label>
            @if(!empty($pessoais->dataNascimentoPessoal))            
                <input type="date" class="form-control" id="dataNascimentoPessoal" name="dataNascimentoPessoal" value="{{ old('dataNascimentoPessoal') ?? $pessoais->dataNascimentoPessoal->format('Y-m-d') ?? '' }}" required>
            @else
                <input type="date" class="form-control" id="dataNascimentoPessoal" name="dataNascimentoPessoal" value="{{ old('dataNascimentoPessoal') }}" required>
            @endif
        </div>          
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="cpf" class="font-weight-bold">{{ __('CPF') }}<span class="text-danger">*</span></label>
            @if(Auth::user()->cpf == Auth::user()->codpes)
                <input type="text" class="form-control" id="cpf" name="cpf" value="" required>
            @else
                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') ?? Auth::user()->cpf }}" required disabled>
            @endif
        </div>
        <div class="col">
            <label for="rg" class="font-weight-bold">{{ __('RG') }}/ RNE / Passaporte<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') ?? Auth::user()->rg }}" required>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Data de Emissão<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dataEmissaoRG" name="dataEmissaoRG" value="{{ old('dataEmissaoRG') ?? $pessoais->dataEmissaoRG ?? '' }}" required>
        </div>
        
        <div class="col-2">
            <label for="ufEmissorRG" class="font-weight-bold">{{ __('Estado') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="ufEmissorRG" name="ufEmissorRG" required>
                <option value="">Selecione o estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado['sglest'] }}" {{ old('ufEmissorRG') == $estado['sglest'] ? "selected" : $pessoais->ufEmissorRG == $estado['sglest'] ? "selected" : "" }}>{{ $estado['nomest'] }}</option>
                @endforeach
            </select> 
        </div> 

        <div class="col">
            <label for="orgaoEmissorRG" class="font-weight-bold">Orgão Emissor<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="orgaoEmissorRG" name="orgaoEmissorRG" value="{{ old('orgaoEmissorRG') ?? $pessoais->orgaoEmissorRG ?? '' }}" required>
        </div>  
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="paisPessoal" class="font-weight-bold">País<span class="text-danger">*</span></label>
            <select class="form-control" id="paisPessoal" name="paisPessoal" required>
                <option value="">Selecione o país</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais['codpas'] }}" {{ old('paisPessoal') == $pais['codpas'] ? "selected" : $pessoais->paisPessoal == $pais['codpas'] ? "selected" : "" }}>{{ $pais['nompas'] }}</option>
                @endforeach
            </select> 
        </div>

        <div class="col" id="exibirEstados">
            <label for="estadoPessoal" class="font-weight-bold">{{ __('Estado') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="estadoPessoal" name="estadoPessoal" required>
                <option value="">Selecione o estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado['sglest'] }}" {{ old('estadoPessoal') == $estado['sglest'] ? "selected" : $pessoais->estadoPessoal == $estado['sglest'] ? "selected" : "" }}>{{ $estado['nomest'] }}</option>
                @endforeach
            </select> 
        </div>

        @if ($pessoais->naturalidadePessoal == "" || $pessoais->estadoPessoal == "")
        <div class="col" id="exibirCidades">                          
            <label for="" class="font-weight-bold">{{ __('Cidade') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="" name="" required disabled>
                <option value="">Selecione o estado primeiro</option>
            </select> 
        </div>
        @else
        <div class="col">
            <label for="naturalidadePessoal" class="font-weight-bold">{{ __('Cidade') }}<span class="text-danger">*</span></label>
            @php
            $cidades = App\Models\Utils::listarLocalidades($pessoais->paisPessoal, $pessoais->estadoPessoal);
            @endphp
            <select class="form-control" id="naturalidadePessoal" name="naturalidadePessoal" required>
                <option value="">Selecione a cidade</option>
                @foreach($cidades as $cidade)
                    <option value="{{ $cidade['codloc'] }}" {{ old('naturalidadePessoal') == $cidade['codloc'] ? "selected" : $pessoais->naturalidadePessoal == $cidade['codloc'] ? "selected" : "" }}>{{ $cidade['cidloc'] }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>        

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="sexoPessoal" class="font-weight-bold">Sexo<span class="text-danger">*</span></label>
            <select class="form-control" id="sexoPessoal" name="sexoPessoal" required>
                <option value="">Selecione o Sexo</option>
                @foreach($sexos as $sexo)
                    <option value="{{ $sexo }}" {{ old('sexoPessoal') == $sexo ? "selected" : $pessoais->sexoPessoal == $sexo ? "selected" : "" }}>{{ $sexo }}</option>
                @endforeach
            </select>            
        </div>
        <div class="col">
            <label for="racaPessoal" class="font-weight-bold">Raça/Cor<span class="text-danger">*</span></label>
            <select class="form-control" id="racaPessoal" name="racaPessoal" required>
                <option value="">Selecione a Raça/Cor</option>
                @foreach($racas as $raca)
                    <option value="{{ $raca }}" {{ old('racaPessoal') == $raca ? "selected" : $pessoais->racaPessoal == $raca ? "selected" : "" }}>{{ $raca }}</option>
                @endforeach
            </select> 
        </div>
        <div class="col">
            <label for="estadoCivilPessoal" class="font-weight-bold">Estado Civil<span class="text-danger">*</span></label>
            <select class="form-control" id="estadoCivilPessoal" name="estadoCivilPessoal" required>
                <option value="">Selecione o Estado Cívil</option>
                @foreach($estados_civil as $estado_civil)
                    <option value="{{ $estado_civil }}" {{ old('estadoCivilPessoal') == $estado_civil ? "selected" : $pessoais->estadoCivilPessoal == $estado_civil ? "selected" : "" }}>{{ $estado_civil }}</option>
                @endforeach
            </select> 
        </div>
        <div class="col">
            <label for="dependentePessoal" class="font-weight-bold">Número de Dependentes</label>
            <input type="number" class="form-control" id="dependentePessoal" name="dependentePessoal" value="{{ old('dependentePessoal') ?? $pessoais->dependentePessoal ?? '' }}" required>
        </div>  
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col-4">                          
            <label for="especialPessoal" class="font-weight-bold">É portador de Necessidades Especiais?<span class="text-danger">*</span></label><br/>
            <select class="form-control" id="especialPessoal" name="especialPessoal" required>
                <option value="">Selecione a opção</option>
                <option value="S" {{ old('especialPessoal') == 'S' ? "selected" : $pessoais->especialPessoal == 'S' ? "selected" : "" }}>Sim</option>
                <option value="N" {{ old('especialPessoal') == 'N' ? "selected" : $pessoais->especialPessoal == 'N' ? "selected" : "" }}>Não</option>
            </select> 
        </div>
        <div class="col">
            <label for="tipoEspecialPessoal" class="font-weight-bold">Qual(is)?<span id="exibirQuais" class="text-danger">*</span></label><br/>  
            <select multiple class="form-control" id="tipoEspecialPessoal" name="tipoEspecialPessoal[]" {{ old('especialPessoal') == 'S' ? "disabled" : $pessoais->especialPessoal == 'S' ? "" : "disabled" }} required>
            @foreach($especiais as $especial)
                <option value="{{ $especial }}" {{ \Illuminate\Support\Str::contains(old('tipoEspecialPessoal'), $especial) ? "selected" : \Illuminate\Support\Str::contains($pessoais->tipoEspecialPessoal, $especial) ? "selected" : "" }}>{{ $especial }}</option>
            @endforeach
            </select>
            
            <small id="tipoEspecialPessoalHelp" class="form-text text-muted">Você pode selecionar mais de uma opção.</small>                    
        </div> 
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">

@if (!empty($pessoais->codigoPessoal))
    <input type="hidden" name="codigoPessoal" value="{{ $pessoais->codigoPessoal }}">
    <input type="hidden" name="codigoDocumento" value="{{ $pessoais->codigoDocumento }}">
    <input type="hidden" name="codigoInscricaoPessoal" value="{{ $pessoais->codigoInscricaoPessoal }}">
    <input type="hidden" name="codigoInscricaoDocumento" value="{{ $pessoais->codigoInscricaoDocumento }}">
@endif