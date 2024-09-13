<div class="form-group">  
    <div class="form-row">
        <div class="col">               
            <label for="nomeAluno" class="font-weight-bold">{{ __('Name') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nomeAluno" name="nomeAluno" value="{{ old('nomeAluno') }}" required maxlength="255">
        </div>

        <div class="col">
            <label for="emailAluno" class="font-weight-bold">{{ __('Email') }}<span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="emailAluno" name="emailAluno" value="{{ old('emailAluno') }}" required maxlength="255">
        </div>

        <div class="col">
            <label for="nacionalidadeAluno" class="font-weight-bold">{{ __('Nacionalidade') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="nacionalidadeAluno" name="nacionalidadeAluno" required>
                <option value="">Selecione uma opção</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais['codpas'] }}" {{ old('nacionalidadeAluno') == $pais['codpas'] ? "selected" : "" }}>{{ $pais['nompas'] }}</option>
                @endforeach
            </select> 
        </div>                   
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="passaporteAluno" class="font-weight-bold">{{ __('Passaporte') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="passaporteAluno" name="passaporteAluno" value="{{ old('passaporteAluno') }}" required>
        </div>

        <div class="col">
            <label for="dataNascimentoAluno" class="font-weight-bold">{{ __('Data de Nascimento') }}<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dataNascimentoAluno" name="dataNascimentoAluno" value="{{ old('dataNascimentoAluno') }}" required>
        </div> 

        <div class="col">
            <label for="codigoPaisAluno" class="font-weight-bold">{{ __('Código do País') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="codigoPaisAluno" name="codigoPaisAluno" required>
                <option value="">Selecione uma opção</option>
                @foreach($paises as $pais)
                    @if (!empty($pais['codddi']))
                        <option value="{{ $pais['codpas'] }}" {{ old('codigoPaisAluno') == $pais['codpas'] ? "selected" : "" }}>+({{ $pais['codddi'] }}) {{ $pais['nompas'] }}</option>
                    @endif
                @endforeach
            </select> 
        </div>

        <div class="col">                            
            <label for="telefoneAluno" class="font-weight-bold">{{ __('Telefone') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="telefoneAluno" name="telefoneAluno" value="{{ old('telefoneAluno') }}" required>
        </div> 
        
        <div class="col">                          
            <label for="sexoAluno" class="font-weight-bold">Sexo<span class="text-danger">*</span></label>
            <select class="form-control" id="sexoAluno" name="sexoAluno" required>
                <option value="">Selecione uma opção</option>
                @foreach($sexos as $sexo)
                    <option value="{{ $sexo }}" {{ old('sexoAluno') == $sexo ? "selected" : "" }}>{{ $sexo }}</option>
                @endforeach
            </select>            
        </div> 
    </div>
</div> 

<div class="form-group">  
    <div class="form-row">
        <div class="col-3">
            <label for="nivelTitulacao" class="font-weight-bold">{{ __('Maior nível de titulação obtido') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="nivelTitulacao" name="nivelTitulacao" required>
                <option value="">Selecione uma opção</option>
                <option value="Graduação" {{ old('nivelTitulacao') == 'Graduação' ? "selected" : "" }}>Graduação</option>
                <option value="Tecnólogo" {{ old('nivelTitulacao') == 'Tecnólogo' ? "selected" : "" }}>Tecnólogo</option>
                <option value="Mestrado" {{ old('nivelTitulacao') == 'Mestrado' ? "selected" : "" }}>Mestrado</option>
            </select> 
        </div>

        <div class="col">
            <label for="iesTitulacao" class="font-weight-bold">{{ __('Instituição de Ensino Superior da Titulação') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="iesTitulacao" name="iesTitulacao" maxlength="255" required>
        </div>

        <div class="col-2">
            <label for="anoTitulacao" class="font-weight-bold">{{ __('Ano de titulação') }}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="anoTitulacao" name="anoTitulacao" maxlength="4" required>
        </div>

        <div class="col-2">
            <label for="codigoPaisTitulacao" class="font-weight-bold">{{ __('País de Titulação') }}<span class="text-danger">*</span></label>
            <select class="form-control" id="codigoPaisTitulacao" name="codigoPaisTitulacao" required>
                <option value="">Selecione uma opção</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais['codpas'] }}" {{ old('codigoPaisTitulacao') == $pais['codpas'] ? "selected" : "" }}>{{ $pais['nompas'] }}</option>
                @endforeach
            </select> 
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-row"> 
        <div class="col-3"> 
            <label class="font-weight-bold mr-2">Possui vínculo empregatício?<span class="text-danger">*</span></label><br/>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vinculoEmpregaticio" id="vinculoEmpregaticioSim" value="Sim">
                <label class="form-check-label" for="vinculoEmpregaticioSim">Sim</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vinculoEmpregaticio" id="vinculoEmpregaticioNao" value="Não">
                <label class="form-check-label" for="vinculoEmpregaticioNao">Não</label>
            </div>
        </div>
        <div class="col"> 
            <label class="font-weight-bold mr-2">Nível<span class="text-danger">*</span></label><br/>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="nivelPrograma" id="nivelProgramaMestrado" value="Mestrado">
                <label class="form-check-label" for="nivelProgramaMestrado">Mestrado</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="nivelPrograma" id="nivelProgramaDoutorado" value="Doutorado">
                <label class="form-check-label" for="nivelProgramaDoutorado">Doutorado</label>
            </div>
        </div>               
    </div>
</div>

<span id="exibirEmpregador">
    <div class="form-group">
        <div class="form-row"> 
            <div class="col-3">
                <label for="tipoEmpregador" class="font-weight-bold">{{ __('Tipo de Empregador') }}<span class="text-danger">*</span></label>
                <select class="form-control" id="tipoEmpregador" name="tipoEmpregador" required>
                    <option value="">Selecione uma opção</option>
                    <option value="Instituição de Ensino Superior no país" {{ old('tipoEmpregador') == 'Instituição de Ensino Superior no país' ? "selected" : "" }}>Instituição de Ensino Superior no país</option>
                    <option value="Instituição de Ensino Superior no exterior" {{ old('tipoEmpregador') == 'Instituição de Ensino Superior no exterior' ? "selected" : "" }}>Instituição de Ensino Superior no exterior</option>
                    <option value="Empresa" {{ old('tipoEmpregador') == 'Empresa' ? "selected" : "" }}>Empresa</option>
                </select> 
            </div>

            <div class="col">
                <label for="nomeEmpregador" class="font-weight-bold">{{ __('Empregador') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nomeEmpregador" name="nomeEmpregador" maxlength="255" required>
            </div>

            <div class="col-2">
                <label for="tipoAfastamento" class="font-weight-bold">{{ __('Tipo de Afastamento') }}<span class="text-danger">*</span></label>
                <select class="form-control" id="tipoAfastamento" name="tipoAfastamento" required>
                    <option value="">Selecione uma opção</option>
                    <option value="Integral" {{ old('tipoAfastamento') == 'Integral' ? "selected" : "" }}>Integral</option>
                    <option value="Parcial" {{ old('tipoAfastamento') == 'Parcial' ? "selected" : "" }}>Parcial</option>
                    <option value="Não informado" {{ old('tipoAfastamento') == 'Não informado' ? "selected" : "" }}>Não informado</option>
                </select> 
            </div>        
        </div>
    </div>

    <div class="form-group">
        <div class="form-row"> 
            <div class="col">
                <label for="categoriaFuncional" class="font-weight-bold">{{ __('Categoria funcional') }}<span class="text-danger">*</span></label>
                <select class="form-control" id="categoriaFuncional" name="categoriaFuncional" required>
                    <option value="">Selecione uma opção</option>
                    <option value="Docente" {{ old('categoriaFuncional') == 'Docente' ? "selected" : "" }}>Docente</option>
                    <option value="Não Docente" {{ old('categoriaFuncional') == 'Não Docente' ? "selected" : "" }}>Não Docente</option>
                </select> 
            </div>

            <div class="col">
                <label for="situacaoSalarial" class="font-weight-bold">{{ __('Situação salarial') }}<span class="text-danger">*</span></label>
                <select class="form-control" id="situacaoSalarial" name="situacaoSalarial" required>
                    <option value="">Selecione uma opção</option>
                    <option value="Com salário" {{ old('situacaoSalarial') == 'Com salário' ? "selected" : "" }}>Com salário</option>
                    <option value="Sem salário" {{ old('situacaoSalarial') == 'Parcial' ? "selected" : "" }}>Sem salário</option>
                </select> 
            </div>

            <div class="col">
                <label for="tempoServico" class="font-weight-bold">{{ __('Tempo global de serviço') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tempoServico" name="tempoServico" maxlength="255" required>
            </div>        

            <div class="col">   
                <label for="tempoServicoMesAno" class="font-weight-bold">&nbsp;</label>
                <input type="text" class="form-control" id="tempoServicoMesAno" name="tempoServicoMesAno" maxlength="255" required>
            </div>
        </div>
    </div>
</span>

<div class="form-group">  
    <label class="font-weight-bold mr-2">Disciplinas<span class="text-danger">*</span></label><br/>
    @foreach($disciplinas as $disciplina)

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="disciplinasGcub[]" id="disciplinasGcub{{$disciplina['sgldis']}}" value="{{ $disciplina['sgldis'] }}">
        <label class="form-check-label" for="disciplinasGcub{{$disciplina['sgldis']}}">
            {{ $disciplina['sgldis'] }}-{{ $disciplina['numseqdis'] }}/{{ $disciplina['numofe'] }} {{ $disciplina['nomdis'] }}</label>
        </label>
    </div>
    @endforeach
</div>

<input type="hidden" name="tipo" value="{{ $tipo }}">