<div class="form-group">  
    <div class="form-row">
        <div class="col">                          
            <label for="cpf" class="font-weight-bold">{{ __('CPF') }}</label>
            <p>{{ $inscricao->cpf }}</p>   
        </div>
        <div class="col">
            <label for="email" class="font-weight-bold">{{ __('Email') }}</label>
            <p>{{ $inscricao->email }}</p>
        </div>   
        <div class="col">                            
            <label for="telefone" class="font-weight-bold">{{ __('Telefone') }}</label>
            <p>{{ $inscricao->telefone }}</p>
        </div>
        <div class="col">
            <label for="dataNascimentoPessoal" class="font-weight-bold">{{ __('Data de Nascimento') }}</label>
            <p>{{ $inscricao->dataNascimentoPessoal->format('d/m/Y') }}</p>
        </div>          
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="rg" class="font-weight-bold">{{ __('RG') }} / RNE / Passaporte</label>
            <p>{{ $inscricao->rg }}</p>
        </div>
        <div class="col">
            <label for="dataEmissaoRG" class="font-weight-bold">Data de Emissão</label>
            <p>{{ date('d/m/Y', strtotime($inscricao->dataEmissaoRG)) }}</p>
        </div>        
        <div class="col">
            <label for="ufEmissorRG" class="font-weight-bold">Orgão Emissor</label>
            <p>{{ $inscricao->orgaoEmissorRG }}/{{ $inscricao->ufEmissorRG }}</p>
        </div> 
        <div class="col">
            <label for="ufEmissorRG" class="font-weight-bold">Sexo</label>
            <p>{{ $inscricao->sexoPessoal }}</p>
        </div> 
    </div>
</div>

@php
    $pais   = App\Models\Utils::obterPais($inscricao->paisPessoal);
    $cidade = App\Models\Utils::obterLocalidade($inscricao->naturalidadePessoal);
    //dd($inscricao);
@endphp

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="naturalidadePessoal" class="font-weight-bold">Naturalidade</label>
            <p>{{ $cidade["cidloc"] }}/{{ $inscricao->estadoPessoal }} - {{ $pais["nompas"] }}</p>
        </div>
        <div class="col">
            <label for="racaPessoal" class="font-weight-bold">Raça</label>
            <p>{{ $inscricao->racaPessoal }}</p>
        </div>        
        <div class="col">
            <label for="dependentePessoal" class="font-weight-bold">Dependentes</label>
            <p>{{  $inscricao->dependentePessoal }}</p>
        </div>         
        <div class="col">
            <label for="tipoEspecialPessoal" class="font-weight-bold">Necessidades Especiais?</label>
            <p>
                @if ($inscricao->especialPessoal == 'S') 
                    Sim {{ $inscricao->tipoEspecialPessoal}}
                @else
                    Não
                @endif
            </p>
        </div> 
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="cepEndereco" class="font-weight-bold">CEP</label>
            <p>{{ $endereco->cepEndereco }}</p>
        </div>
        <div class="col">
            <label for="logradouroEndereco" class="font-weight-bold">Endereço</label>
            <p>{{ $endereco->logradouroEndereco }}, {{ $endereco->numeroEndereco }} {{ $endereco->complementoEndereco }}</p>
        </div>        
        <div class="col">
            <label for="bairroEndereco" class="font-weight-bold">Bairro</label>
            <p>{{ $endereco->bairroEndereco }}</p>
        </div>         
        <div class="col">
            <label for="bairroEndereco" class="font-weight-bold">Cidade/UF</label>
            <p>{{ $endereco->localidadeEndereco }}/{{ $endereco->ufEndereco }}</p>
        </div>   
    </div>
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="nomePessoaEmergencia" class="font-weight-bold">Pessoa a ser notificada em caso de Emergência</label>
            <p>{{ $emergencia->nomePessoaEmergencia }}</p>
        </div>
        <div class="col">
            <label for="logradouroEndereco" class="font-weight-bold">Telefone</label>
            <p>{{ $emergencia->telefonePessoaEmergencia }}</p>
        </div>        
        <div class="col"></div>
    </div>
</div>