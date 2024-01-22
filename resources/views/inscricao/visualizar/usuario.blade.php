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
                @if ($doutorado)
                    <th scope="col">3.2.1<br/>Pré-projeto</th>
                @endif
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
</div>