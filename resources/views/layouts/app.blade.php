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
    // Exemplo de JavaScript inicial para desativar envios de formulario, se houver campos invalidos.
    (function() 
    {
        'use strict';
        window.addEventListener('load', function() {
        // Pega todos os formularios que nos queremos aplicar estilos de validacaoo Bootstrap personalizados.
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

    $(document).ready(function()
    {
        $('#modalAviso').modal('show');

        $("#success-alert").fadeTo(2000, 500).slideUp(500, function()
        {
            $("#success-alert").slideUp(500);
        });

        function limpa_formulário_cep() 
        {
            // Limpa valores do formulário de cep.
            $("#logradouro").val("");
            $("#bairro").val("");
            $("#localidade").val("");
            $("#uf").val("");
        }

        //Quando o campo cep perde o foco.
        $("#cep").blur(function() 
        {        
          //Nova variável "cep" somente com dígitos.
          var cep = $(this).val().replace(/\D/g, '');

          //Verifica se campo cep possui valor informado.
          if (cep != "") 
          {            
              //Expressão regular para validar o CEP.
              var validacep = /^[0-9]{8}$/;

              //Valida o formato do CEP.
              if(validacep.test(cep)) {
                  //Preenche os campos com "..." enquanto consulta webservice.
                  $("#logradouro").val("...");
                  $("#bairro").val("...");
                  $("#cidade").val("...");
                  $("#uf").val("...");

                  //Consulta o webservice viacep.com.br/
                  $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                      if (!("erro" in dados)) {
                          //Atualiza os campos com os valores da consulta.
                          $("#logradouro").val(dados.logradouro);
                          $("#bairro").val(dados.bairro);
                          $("#localidade").val(dados.localidade);
                          $("#uf").val(dados.uf);
                          $("#ibge").val(dados.ibge);
                      } //end if.
                      else {
                          //CEP pesquisado não foi encontrado.
                          limpa_formulario_cep();
                          alert("CEP não encontrado.");
                      }
                  });
              } //end if.
              else {
                  //cep é inválido.
                  limpa_formulario_cep();
                  alert("Formato de CEP inválido.");
              }
          } //end if.
          else {
              //cep sem valor, limpa formulário.
              limpa_formulario_cep();
          }
        });
    })
  </script>
@endsection