<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="name" class="font-weight-bold">{{ __('Name') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>
        <div class="col">
            <label for="email" class="font-weight-bold">{{ __('Email') }}<span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>   
        <div class="col">
            <label for="dataNascimentoPessoal" class="font-weight-bold">{{ __('Data de Nascimento') }}<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dataNascimentoPessoal" name="dataNascimentoPessoal" value="" required>
        </div>          
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="cpf" class="font-weight-bold">{{ __('CPF') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" required disabled>
        </div>
        <div class="col">
            <label for="rg" class="font-weight-bold">{{ __('RG') }}/ RNE / Passaporte<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}" required>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Data de Emissão<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dataEmissaoRG" name="dataEmissaoRG" value="{{ old('dataEmissaoRG') }}" required>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Orgão Emissor<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dataEmissaoRG" name="dataEmissaoRG" value="{{ old('dataEmissaoRG') }}" required>
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
                    @if ($pais['codpas'] == 1)
                        <option value="{{ $pais['codpas'] }}" selected>{{ $pais['nompas'] }}</option>
                    @else
                        <option value="{{ $pais['codpas'] }}">{{ $pais['nompas'] }}</option>
                    @endif
                @endforeach
            </select> 
        </div>

        <div class="col" id="exibirEstados">
            <label for="estadoPessoal" class="font-weight-bold">{{ __('Estado') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="estadoPessoal" name="estadoPessoal" required>
                <option value="">Selecione o estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado['sglest'] }}">{{ $estado['nomest'] }}</option>
                @endforeach
            </select> 
        </div>

        <div class="col" id="exibirCidades">                          
            <label for="naturalidadePessoal" class="font-weight-bold">{{ __('Cidade') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="estadoPessoal" name="naturalidadePessoal" required disabled>
                <option value="">Selecione o estado primeiro</option>
            </select> 
        </div>
    </div>
</div>        

<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="sexoPessoal" class="font-weight-bold">Sexo<span class="text-danger">*</span></label>
            <select class="form-control" id="sexoPessoal" name="sexoPessoal" required>
                <option value="">Selecione o Sexo</option>
                @foreach($sexos as $sexo)
                    <option value="{{ $sexo }}">{{ $sexo }}</option>
                @endforeach
            </select>            
        </div>
        <div class="col">
            <label for="racaPessoal" class="font-weight-bold">Raça/Cor<span class="text-danger">*</span></label>
            <select class="form-control" id="racaPessoal" name="racaPessoal" required>
                <option value="">Selecione a Raça/Cor</option>
                @foreach($racas as $raca)
                    <option value="{{ $raca }}">{{ $raca }}</option>
                @endforeach
            </select> 
        </div>
        <div class="col">
            <label for="estadoCivilPessoal" class="font-weight-bold">Estado Civil<span class="text-danger">*</span></label>
            <select class="form-control" id="estadoCivilPessoal" name="estadoCivilPessoal" required>
                <option value="">Selecione o Estado Cívil</option>
                @foreach($estados_civil as $estado_civil)
                    <option value="{{ $estado_civil }}">{{ $estado_civil }}</option>
                @endforeach
            </select> 
        </div>
        <div class="col">
            <label for="dependentePessoal" class="font-weight-bold">Número de Dependentes</label>
            <input type="number" class="form-control" id="dependentePessoal" name="dependentePessoal" value="{{ old('dependentePessoal') }}" required>
        </div>  
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col-3">                          
            <label for="especialPessoal" class="font-weight-bold">É portador de Necessidades Especiais?<span class="text-danger">*</span></label><br/>
            <select class="form-control" id="especialPessoal" name="especialPessoal" required>
                <option value="">Selecione a opção</option>
                <option value="S">Sim</option>
                <option value="N">Não</option>
            </select> 
        </div>
        <div class="col">
            <label for="tipoEspecialPessoal" class="font-weight-bold">Qual(is)?<span id="exibirQuais" class="text-danger">*</span></label><br/>  
            <select multiple class="form-control" id="tipoEspecialPessoal" name="tipoEspecialPessoal[]" disabled required>
            @foreach($especiais as $especial)
                <option value="{{ $especial }}">{{ $especial }}</option>
            @endforeach
            </select>
            <small id="tipoEspecialPessoalHelp" class="form-text text-muted">Você pode selecionar mais de uma opção.</small>                    
        </div> 
    </div>
</div>

@if (!empty($codigoInscricao))
<div class="card bg-default">
    <h5 class="card-header">Anexo(s)</h5>
        <div class="card-body">
        @if (count($arquivos) == 0)
            <div class="alert alert-warning">Nenhum documento cadastrado</div>
        @else                
            <div class="table-responsive">
                <table class="table">
                @foreach ($arquivos as $arquivo)
                    @php
                        $arquivo_inscricao .= $arquivo->codigoArquivo."|";
                    @endphp
                    <tr>
                        <th>{{ $arquivo->tipoDocumento }}</th>
                        <td>
                            <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="arquivo/{{ $arquivo->codigoArquivo }}/editar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" title="Alterar">
                                <i class="fa fa-wrench"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach                   
                </table>  
            </div>                
        @endif
    </div>
</div>

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoArquivoInscricao" value="{{ $arquivo_inscricao }}">    
@endif


