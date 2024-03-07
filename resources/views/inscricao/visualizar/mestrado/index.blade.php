<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th scope="col" colspan="8">Documentos Obrigatórios</th>                               
            </tr>
            <tr class="text-center">
                <th scope="col">3.1.1<br/>Foto</th>
                <th scope="col">3.1.2<br/>Ficha de Inscrição</th>
                <th scope="col">3.1.3<br/>CPF/Passaporte</th>
                <th scope="col">3.1.4<br/>RG</th>
                <th scope="col">3.1.5<br/>RNE</th>
                <th scope="col">3.1.6<br/>Certificado/Diploma</th>
                <th scope="col">3.1.7<br/>Histórico Escolar</th>
                <th scope="col">3.1.8<br/>Currículo Lattes/Vittae</th>
            </tr>
        </thead>                        
        <tr class="text-center">
            <td>
                @if(!empty($foto))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td>
                @if(!empty($requerimento))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td>
                @if(!empty($cpf))                            
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td>
                @if(!empty($rg))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif                                
            </td>
            <td>
                @if(!empty($rne))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif                                  
            </td>
            <td>
                @if(!empty($diplomas))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif                               
            </td>
            <td>
                @if(!empty($historicos))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif  
            </td>
            <td>
                @if(!empty($curriculo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif  
            </td>
            @if ($doutorado)
            <td>
                @if(!empty($projeto))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif                                
            </td>
            @endif
        </tr>
    </table>

    @if ($codigoEdital > 3)
        @if (Session::get('total')['especial'] >= 10 && (empty($requerimento) && (Session::get('total')['foto'] == 1)))
            <!-- Validation Errors -->
            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                            
            <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
                @csrf

                <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
                <input type="hidden" name="codigoTipoDocumento" value="28">
                <input type="hidden" name="codigoTipo" value="ppgpe">

                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar Inscrição</button>
            </form>

            <!-- Modal -->
            @include('utils.loader')        
        @endif
    @endif
</div>