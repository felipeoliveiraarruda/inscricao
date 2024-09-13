<!-- Button trigger modal -->
<button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#comprovanteModal">Upload</button>

<!-- Modal -->
<div class="modal fade" id="comprovanteModal" tabindex="-1" aria-labelledby="comprovanteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comprovanteModalLabel">Enviar Comprovante de Inscrição</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="errors" class="alert alert-block alert-danger"></div>
                @include('arquivo.partials.comprovante')
            </div>
        </div>
    </div>
</div>