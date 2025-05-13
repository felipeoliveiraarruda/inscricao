@extends('layouts.app')

@section('content')

<div class="modal modal-blur fade" id="modalAviso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ $item['title'] }}</h6>
            </div>
            <div class="modal-body">
                <p class="text-danger text-center">{{ $item['story'] }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->previous() }}" role="button" aria-pressed="true" class="btn btn-primary">Fechar</a>
            </div>
        </div>
    </div>
</div>
@endsection