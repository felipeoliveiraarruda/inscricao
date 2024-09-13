<!-- Button trigger modal -->
<button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#alterarModal">Upload</button>

<!-- Modal -->
<div class="modal fade" id="alterarModal" tabindex="-1" aria-labelledby="alterarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comprovanteModalLabel">Alterar Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="errors" class="alert alert-block alert-danger"></div>
                
                <div class="form-group">
                    <input type="file" class="form-control-file" id="arquivo" name="arquivo" required>
                </div>

                <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
            </div>
        </div>
    </div>
</div>