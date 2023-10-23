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
  <script src="https://cdn.tiny.cloud/1/6gp4wemfll2rht9wyhq9idfb5v2h2e2sv8fd9yqkp9nh5337/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  
  
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
        $("#exibirQuais").hide();
        $("#exibirTipoDocumentoPessoal").hide();
        $("#exibirArquivoDocumentoPessoal").hide();

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

        $("#paisPessoal").change(function() 
        {  
          $.ajax({          
            url: "/estados/"+$("#paisPessoal").val(),
            type: "get",          
            success: function(response)
            {
              $("#exibirEstados").html(response);
            },
          });        
        });

        $("#estadoPessoal").change(function() 
        {  
          $.ajax({          
            url: "/cidades/"+$("#paisPessoal").val()+"/"+ $("#estadoPessoal").val(),
            type: "get",          
            success: function(response)
            {
              $("#exibirCidades").html(response);
            },
          });
        });

        $("#especialPessoal").change(function() 
        {  
          if ($("#especialPessoal").val() == 'S')
          {
            $("#tipoEspecialPessoal").prop('disabled', false);
            $("#exibirQuais").show();
          }
          else
          {
            $("#tipoEspecialPessoal").prop('disabled', true);
            $("#exibirQuais").hide();
          }          
        });

        $("#cadastrarDocumentoPessoal").click(function() 
        {
          if ($("#codigoTipoDocumento").val() == "" || $("#arquivo").val() == "")
          {
            $("#exibirTipoDocumentoPessoal").show();
            $("#exibirArquivoDocumentoPessoal").show();
          }
          else
          {
            $("#exibirTipoDocumentoPessoal").hide();
            $("#exibirArquivoDocumentoPessoal").hide();
          }           
        });

        $("#formEnviar").on("submit", function()
        {
          $('#finalizarModal').modal('hide');
          $('#loaderModal').modal('show')
        });

        $("#mesmoEndereco").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#mostrarEnderecoEmergencia").hide();
          }
          else
          {
            $("#mostrarEnderecoEmergencia").show();
          }
        });

        $("#dadosBolsa").hide();

        $("#inlineBolsaSim").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#solicitarBolsa").hide();
            $("#dadosBolsa").show();

            $("#orgaoRecursoFinanceiro").prop('required',true);
            $("#inicioRecursoFinanceiro").prop('required',true);
            $("#finalRecursoFinanceiro").prop('required',true);
          }
        });

        $("#inlineBolsaNao").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#solicitarBolsa").show();
            $("#dadosBolsa").hide();

            $("#orgaoRecursoFinanceiro").prop('required',false);
            $("#inicioRecursoFinanceiro").prop('required',false);
            $("#finalRecursoFinanceiro").prop('required',false);
          }
        });
    })  

    /*tinymce.init({
      selector: 'textarea',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tableofcontents footnotes mergetags autocorrect',
      toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright lineheight alignjustify | link image table mergetags | checklist numlist bullist indent outdent | removeformat',
      /*tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',   
    }); */
  </script>
@endsection