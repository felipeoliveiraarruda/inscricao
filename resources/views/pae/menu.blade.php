<div class="list-group">
    <!--<a href="#" class="list-group-item list-group-item-action">Desempenho Acadêmico</a>
    <a href="#" class="list-group-item list-group-item-action">Análise de Currículo Lattes</a>
    <a href="#" class="list-group-item list-group-item-action">Documentação</a>-->

	@if ($inscricao->statusInscricao == 'N' && date('Y-m-d') < '2025-05-05')
		@if ($total)
			<a href="inscricao/{{ $codigoEdital }}/pae/finalizar" class="list-group-item list-group-item-action" style="background-color: #26385C; color: white;">Enviar Documentação</a>
			
			<!-- Modal data-toggle="modal" data-target="#finalizarModal" -->
			<div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="finalizarModalLabel">Inscrição PAE {{ $anosemestre }}</h5>
						</div>

						<div class="modal-body text-justify">
							Ao enviar a sua inscrição, você está de acordo com todos os critério de seleção contidos no edital de seleção.<br/><br/>

							Após o envio da inscrição os dados cadastrados não poderão ser alterados.<br/>
						</div>
						<div class="modal-footer" id="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
							<x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
				
							<form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoEdital }}/pae/finalizar/store">
								@csrf                   
								<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
								<button type="submit" class="btn btn-primary" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="loaderModal" tabindex="-1" aria-labelledby="loaderModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">		
						<div class="modal-body text-justify">
							<div id="loader">
								<div class="spinner-grow text-primary" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-secondary" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-success" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-danger" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-warning" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-info" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-light" role="status">
									<span class="sr-only"></span>
								</div>
								<div class="spinner-grow text-dark" role="status">
									<span class="sr-only"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	@endif

    <a href="dashboard" class="list-group-item list-group-item-action ">Voltar</a>
</div>