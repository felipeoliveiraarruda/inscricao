@extends('layouts.app')

@section('content')

<div class="modal modal-blur fade" id="modalAviso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Importar Dados</h6>
            </div>
            <div class="modal-body">
                Você possui dados já cadastrados em nosso sistema. Deseja importar essas informações?
            </div>
            <div class="modal-footer">
                <a href="{{ env('APP_URL') }}" role="button" aria-pressed="true" class="btn btn-primary">Sim</a>
                <a href="{{ env('APP_URL') }}" role="button" aria-pressed="true" class="btn btn-primary">Não</a>
            </div>
        </div>
    </div>
</div>

@endsection