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
        $("#exibirQuais").hide();
        $("#exibirTipoDocumentoPessoal").hide();
        $("#exibirArquivoDocumentoPessoal").hide();
        $("#exibirEmpregador").hide();

        $('#modalAviso').modal('show');
        $("#mostrarEnderecoEmergencia").hide();
        $("#mostraAnoEspecial").hide();

        $("#mostraAnoEspecial").hide();

        $("#exibirRegular").hide();
        $("#exibirNaoRegular").hide();

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
          //Nova variavel "cep" somente com dígitos.
          var cep = $(this).val().replace(/\D/g, '');

          //Verifica se campo cep possui valor informado.
          if (cep != "") 
          {            
              //Expressao regular para validar o CEP.
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
                          //CEP pesquisado nao foi encontrado.
                          limpa_formulario_cep();
                          alert("CEP não encontrado.");
                      }
                  });
              } //end if.
              else {
                  //cep invalido
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
          if ($("#paisPessoal").val() == 1)
          {
            $.ajax({          
              url: "estados/"+$("#paisPessoal").val(),
              type: "get",          
              success: function(response)
              {
                $("#exibirEstados").html(response);
              },
            }); 
          }
          else
          {
            $.ajax({          
              url: "estados/"+$("#paisPessoal").val(),
              type: "get",          
              success: function(response)
              {
                $("#exibirEstados").html(response);
              },
            }); 

            console.log('Carrega estados');

            $.ajax({          
              url: "cidades/"+$("#paisPessoal").val()+"/''",
              type: "get",          
              success: function(response)
              {
                $("#exibirCidades").html(response);
              },
            });

            console.log('Carrega cidades');
          }
        });

        $("#estadoPessoal").change(function() 
        {  
          $.ajax({          
            url: "cidades/"+$("#paisPessoal").val()+"/"+ $("#estadoPessoal").val(),
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
          $('#loaderModal').modal('show');
        });

        $("#validarInscricao").click(function()
        {
          $('#loaderModal').modal('show');
        });

        $("#mesmoEndereco").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#mostrarEnderecoEmergencia").hide();

            $("#cep").prop('required',false);
            $("#logradouro").prop('required',false);
            $("#numero").prop('required',false);
            $("#bairro").prop('required',false);
            $("#cidade").prop('required',false);
            $("#uf").prop('required',false);
          }
        });

        $("#novoEndereco").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#mostrarEnderecoEmergencia").show();

            $("#cep").prop('required',true);
            $("#logradouro").prop('required',true);
            $("#numero").prop('required',true);
            $("#bairro").prop('required',true);
            $("#cidade").prop('required',true);
            $("#uf").prop('required',true);
          }
        });

        $("#dadosBolsa").hide();
        $("#solicitarBolsaDados").hide();

        if ($("#inlineBolsaSim").prop('checked')) 
        {
            $("#orgaoRecursoFinanceiro").prop('required',true);
            $("#tipoBolsaFinanceiro").prop('required',true);
            $("#inicioRecursoFinanceiro").prop('required',true);
            $("#finalRecursoFinanceiro").prop('required',true);

            $("#dadosBolsa").show();
        }

        if ($("#inlineSolicitarSim").prop('checked')) 
        {
          $("#orgaoRecursoFinanceiro").prop('required',false);
          $("#tipoBolsaFinanceiro").prop('required',false);
          $("#inicioRecursoFinanceiro").prop('required',false);
          $("#finalRecursoFinanceiro").prop('required',false);

          $("#anoTitulacaoRecursoFinanceiro").prop('required',true);
          $("#iesTitulacaoRecursoFinanceiro").prop('required',true);
          /*$("#agenciaRecursoFinanceiro").prop('required',true);
          $("#contaRecursoFinanceiro").prop('required',true);
          $("#localRecursoFinanceiro").prop('required',true);*/

          $("#solicitarBolsaDados").show();
        }

        $("#inlineBolsaSim").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#solicitarBolsa").hide();
            $("#dadosBolsa").show();

            $("#orgaoRecursoFinanceiro").prop('required',true);
            $("#tipoBolsaFinanceiro").prop('required',true);
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
            $("#tipoBolsaFinanceiro").prop('required',false);
            $("#inicioRecursoFinanceiro").prop('required',false);
            $("#finalRecursoFinanceiro").prop('required',false);
          }
        });

        $("#inlineSolicitarSim").click(function() 
        {
          if ($(this).prop('checked')) 
          {

            $("#dadosBolsa").hide();
            $("#solicitarBolsaDados").show();

            $("#anoTitulacaoRecursoFinanceiro").prop('required',true);
            $("#iesTitulacaoRecursoFinanceiro").prop('required',true);
            /*$("#agenciaRecursoFinanceiro").prop('required',true);
            $("#contaRecursoFinanceiro").prop('required',true);
            $("#localRecursoFinanceiro").prop('required',true);*/
          }
        });

        $("#inlineSolicitarNao").click(function() 
        {
          if ($(this).prop('checked')) 
          {

            $("#dadosBolsa").hide();
            $("#solicitarBolsaDados").hide();

            $("#anoTitulacaoRecursoFinanceiro").prop('required',false);
            $("#iesTitulacaoRecursoFinanceiro").prop('required',false);
            $("#agenciaRecursoFinanceiro").prop('required',false);
            $("#contaRecursoFinanceiro").prop('required',false);
            $("#localRecursoFinanceiro").prop('required',false);
          }
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('select[name^="aceitarDocumento"]').change(function() {

          var valor = $(this).val();
          var indice = valor.split("|");

          var documento     = 'select[name^="tipoDocumentoAnalise['+indice[1]+']"]';
          var justificativa = 'input[name^="justificativaAnalise['+indice[1]+']"';
          var pontuacao     = 'input[name^="pontuacaoAnalise['+indice[1]+']"';

          if (indice[0] == 'N')
          {    
            $(documento).prop('required', true);
            $(documento).prop('disabled', false);

            $(justificativa).prop('required', true);
            $(justificativa).prop('disabled', false);

            $(pontuacao).val('1');
          }
          else if (indice[0] == 'S')
          {
            $(documento).prop('required', false);
            $(documento).prop('disabled', true);

            $(justificativa).prop('required', false);
            $(justificativa).prop('disabled', true);

            $(pontuacao).val('1');
          }
          else if (indice[0] == 'R')
          {
            $(documento).prop('required', false);
            $(documento).prop('disabled', true);

            $(justificativa).prop('required', false);
            $(justificativa).prop('disabled', false);

            $(pontuacao).val('0');
          }
        });

        $("#vinculoEmpregaticioSim").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#exibirEmpregador").show();

            $("#tipoEmpregador").prop('required',true);
            $("#nomeEmpregador").prop('required',true);
            $("#tipoAfastamento").prop('required',true);
            $("#categoriaFuncional").prop('required',true);
            $("#situacaoSalarial").prop('required',true);
            $("#tempoServico").prop('required',true);
            $("#tempoServicoMesAno").prop('required',true);
          }
        });

        $("#vinculoEmpregaticioNao").click(function() 
        {
          if ($(this).prop('checked')) 
          {
           $("#exibirEmpregador").hide();

            $("#tipoEmpregador").prop('required',false);
            $("#nomeEmpregador").prop('required',false);
            $("#tipoAfastamento").prop('required',false);
            $("#categoriaFuncional").prop('required',false);
            $("#situacaoSalarial").prop('required',false);
            $("#tempoServico").prop('required',false);
            $("#tempoServicoMesAno").prop('required',false);
          }
        });

        $('input[name^="arquivoGcub"]').change(function() 
        {
          document.getElementById('_'+$(this).attr('name')).innerHTML = '<i class="fa fa-check text-success"></i>';
        });

        $("#alunoEspecialS").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#mostraAnoEspecial").show();

            $("#dataAlunoEspecial").prop('required',true);
          }
        });

        $("#alunoEspecialN").click(function() 
        {
          if ($(this).prop('checked')) 
          {
            $("#mostraAnoEspecial").hide();

            $("#dataAlunoEspecial").prop('required',false);
          }
        });

        $("#checkTodosDeferimento").click(function(){
   	      $('input:checkbox').prop('checked', $(this).prop('checked'));
        });

        $('input[name^="deferimentoCandidato"]').change(function()
        {
          var countShared = $('input[name^="deferimentoCandidato"]');

          console.log($(this).siblings(':checked').lenght());
          
          /*if(countShared > LIMITE_EM_NUMEROS) 
          {              
            $(this).prop(‘checked’,false);
          });*/
        });

        if ($("#paisPessoal").val() == 1)
        {
          $("#mostrarDocumentoNacional").show();
          $("#mostrarDocumentoInternacional").hide();

          $("#tipoDocumento").prop('required',false);
          $("#numeroDocumento").prop('required',false);

          $("#numeroRG").prop('required',true);
          $("#ufEmissorRG").prop('required',true);
          $("#orgaoEmissorRG").prop('required',true);
        }
        else
        {
          $("#mostrarDocumentoNacional").hide();
          $("#mostrarDocumentoInternacional").show();

          $("#tipoDocumento").prop('required',true);
          $("#numeroDocumento").prop('required',true);

          $("#numeroRG").prop('required',false);
          $("#ufEmissorRG").prop('required',false);
          $("#orgaoEmissorRG").prop('required',false);
        }

        $("#paisPessoal").change(function() 
        { 
          if ($("#paisPessoal").val() == 1)
          {
            $("#mostrarDocumentoNacional").show();
            $("#mostrarDocumentoInternacional").hide();

            $("#tipoDocumento").prop('required',false);
            $("#numeroDocumento").prop('required',false);

            $("#numeroRG").prop('required',true);
            $("#ufEmissorRG").prop('required',true);
            $("#orgaoEmissorRG").prop('required',true);

          }
          else
          {
            $("#mostrarDocumentoNacional").hide();
            $("#mostrarDocumentoInternacional").show();

            $("#tipoDocumento").prop('required',true);
            $("#numeroDocumento").prop('required',true);

            $("#numeroRG").prop('required',false);
            $("#ufEmissorRG").prop('required',false);
            $("#orgaoEmissorRG").prop('required',false);
          }
        });

        $("#telefonePessoaEmergencia").blur(function()
        {
          if ($("#telefonePessoaEmergencia").val() != '')
          {
            $.ajax({          
              url: "/telefone/"+$("#telefonePessoaEmergencia").val(),
              type: "get",          
              success: function(response)
              {
                if (response > 0)
                {
                  $("#telefonePessoaEmergencia").val('');
                  $("#telefoneAjudaBlock").html('Telefone não pode ser igual ao telefone cadastrado');
                }
                else
                {
                  $("#telefoneEmergencia").prop('required',false);
                  $("#telefoneAjudaBlock").html('');
                }
              },
            });
          }
        });

      $("#exibirCampos").hide();

      $("#inlineRadioPassaporte").click(function() 
      {
        //$("#cpfVerificar").prop('disabled', true);
        $("#nomeDocumento").html('Passaporte<span class="text-danger">*</span>');
        $("#cpfVerificar").prop('maxlenght', 11);
        $('#cpfVerificar').unmask();
        $("#cpfVerificar").prop('disabled', false);
        
      });

      $("#inlineRadioCPF").click(function() 
      {
        //$("#cpfVerificar").prop('disabled', true);
        $("#nomeDocumento").html('CPF<span class="text-danger">*</span>');
        $('#cpfVerificar').mask('000.000.000-00');
        $("#cpfVerificar").prop('maxlenght', 14);
        $("#cpfVerificar").prop('disabled', false);
      });

      $("#btnProsseguir").click(function() 
      {
        if ($("#cpfVerificar").val() != '')
        {
          $.ajax(
          {          
            url: "verificacao/"+$("#cpfVerificar").val(),
            type: "get",

            success: function(response)
            { 
              $("#btnProsseguir").hide();
              $("#cpfVerificar").prop('disabled', true);
              
              if (response == 0)
              {
                $("#exibirCampos").show();   
                $("#cpf").prop('value', $("#cpfVerificar").val());
              }
              else
              {
                $("#exibirCampos").html('<div class="mb-4 text-sm text-danger text-center">Documento '+$("#cpfVerificar").val()+' já está cadastrado.</div><div class="mb-4 text-sm text-justify text-gray-600">Acesse o sistema através do menu "Entrar" no canto superior direito com seu e-mail e senha cadastrados.</div><div class="mb-4 text-sm text-justify text-gray-600">Ao criar a sua conta em nosso sistema um e-mail de verificação foi enviado ao e-mail cadastrado. Verifique se sua caixa de e-mail não está cheia ou se o e-mail enviado caiu em sua caixa de "SPAM"</div>');
                $("#exibirCampos").show();
              }
            },
          });
        }
      });

      $("#egressoRegularSim").click(function() 
      {
        $("#exibirRegular").show();
        $("#exibirNaoRegular").hide();
        $("#egressoAtividade").prop('required', true);
      });

      $("#egressoRegularNao").click(function() 
      {
        $("#exibirRegular").hide();
        $("#exibirNaoRegular").show();
        $("#egressoAtividade").prop('required', false);
      });

      $('#exibirIdioma').show();

      $('#save-idioma').click(function(e)
		  {			
        var fields = false;
        var idiomas = {};
        
        $("input[data-idioma='true']").each(function() 
        {
            var element = $(this);			
            
          if (element.val() == "") 
          {
            element.addClass('is-invalid');
            fields = true;
            }
          else
          {
            element.removeClass('is-invalid');
            fields = false;
            idiomas[$(this).attr('name')] = $(this).val();
          }
        });

        $("select[data-idioma='true']").each(function() 
        {
            var element = $(this);
            
          if (element.val() == "") 
          {
            element.addClass('is-invalid');
            fields = true;
            }
          else
          {
            element.removeClass('is-invalid');
            fields = false;
            idiomas[$(this).attr('name')] = $(this).find(":selected").val();
          }
        });
        
        if (fields === false)
        {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          }); 

          $.ajax(
          {
            type: "POST",					
            url: "{{ url('idiomas/store') }}",
            data:  { idiomas: idiomas },
                              
            success:function(response)
            {
              $('#exibirIdioma').html(response);
              $("input[data-idioma='true']").val('');
              $("select[data-idioma='true']").val('""');
                      
              $('#addIdioma').modal("hide");
            },
            error: function(error)
            {                   	
              alert("Ocorreu um erro no cadastro do idioma");
            }
          });
        }			
      });
  });




    /*tinymce.init({
      selector: 'textarea',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tableofcontents footnotes mergetags autocorrect',
      toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright lineheight alignjustify | link image table mergetags | checklist numlist bullist indent outdent | removeformat',
      /*tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',   
    }); */
  </script>
@endsection