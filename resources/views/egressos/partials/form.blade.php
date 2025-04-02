<div class="form-group">
    <div class="form-row">
        <div class="col">  
			<label for="egressoNome" class="font-weight-bold">Nome Completo<span class="text-danger">*</span></label>
          	<input type="text" class="form-control" id="egressoNome" name="egressoNome" value="{{ old('egressoNome') }}" required>
		</div>
		<div class="col">  
			<label for="egressoEmail" class="font-weight-bold">E-mail<span class="text-danger">*</span></label>
			<input type="email" class="form-control" id="egressoEmail" name="egressoEmail" value="{{ old('egressoEmail') }}" required >
		</div>
	</div>
</div>

<div class="form-group">  
    <p class="mb-1"><label for="egressoRegular" class="font-weight-bold">Foi aluno regularmente matriculado no PPGEM - EEL/USP?<span class="text-danger">*</span></label></p>
    
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="egressoRegular" id="egressoRegularSim" value="S">
        <label class="form-check-label" for="egressoRegularSim">Sim</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="egressoRegular" id="egressoRegularNao" value="N">
        <label class="form-check-label" for="egressoRegularNao">Não</label>
    </div>
</div>

<div id="exibirRegular">
	<div class="form-group">
		<p class="mb-1"><label for="egressoNivel" class="font-weight-bold">Último Nível obtido no PPGEM - EEL/USP<span class="text-danger">*</span></label></p>
		
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="egressoNivel" id="egressoNivelMestrado" value="Mestrado Concluído">
			<label class="form-check-label" for="egressoNivelMestrado">Mestrado Concluído</label>
		</div>

		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="egressoNivel" id="egressoNivelDoutorado" value="Doutorado Concluído">
			<label class="form-check-label" for="egressoNivelDoutorado">Doutorado Concluído</label>
		</div>

		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="egressoNivel" id="egressoNivelCurso" value="Não Concluí o Curso">
			<label class="form-check-label" for="egressoNivelCurso">Não Concluí o Curso</label>
		</div>
	</div>

	<div class="form-group">
		<label for="egressoLocal" class="font-weight-bold">Instituição/Empresa e Atividade de Atuação</label>
		<input type="text" class="form-control" id="egressoLocal" name="egressoLocal" value="{{ old('egressoLocal') }}" >
	</div>

	<div class="form-group">
		<label for="egressoAtividade" class="font-weight-bold">Sua atividade está relacionada com a formação acadêmica<span class="text-danger">*</span></label>
		<input type="text" class="form-control" id="egressoAtividade" name="egressoAtividade" value="{{ old('egressoAtividade') }}" required>
	</div>
</div>

<div id="exibirNaoRegular">
	
</div>