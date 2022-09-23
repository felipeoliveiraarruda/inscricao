@extends('laravel-usp-theme::master')

@section('title') 
  @parent 
@endsection

@section('styles')
  @parent
  <style>
    /*seus estilos*/
  </style>
@endsection

@section('javascripts_bottom')
  @parent
  <script type="text/javascript" src="{{ asset('js/mascara.js') }}"></script>

  <script>
    // Exemplo de JavaScript inicial para desativar envios de formul�rio, se houver campos inv�lidos.
    (function() 
    {
        'use strict';
        window.addEventListener('load', function() {
        // Pega todos os formul�rios que n�s queremos aplicar estilos de valida��o Bootstrap personalizados.
        var forms = document.getElementsByClassName('needs-validation');
        // Faz um loop neles e evita o envio
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
    })();
  </script>
@endsection