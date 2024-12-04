@if (Session::get('total')['mestrado'] >= 5 && $total >= 6 && !empty($requerimento) && $status == 'N')
        <!-- Validation Errors -->
        <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

        <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
            @csrf
                                                            
            <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
            <input type="hidden" name="codigoTipo" value="ppgpe">

            <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Finalizar Inscrição</button>
        </form>

        <!-- Modal -->
        @include('utils.loader')
    </div>
@endif

<table class="table table-striped">
    <thead>
        <tr class="text-center">
            <th scope="col" colspan="3">Documentos Obrigatórios</th>
        </tr>
    </thead> 
    <tbody>
        <tr>
            <td>1.1 Requerimento de Inscrição (Arquivo será gerado após a submissão dos documento obrigatórios)<span class="text-danger">*</span>            
                @if (Session::get('total')['mestrado'] >= 5 && $total >= 5)     
                 - <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new">Imprimir</a>                
                @endif
            </td>   
            <td class="text-center">
                @if(!empty($requerimento->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td class="text-center">
                @if (!empty($requerimento->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$requerimento->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $requerimento->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $requerimento->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    @if (Session::get('total')['mestrado'] >= 5 && $total >= 5)
                    <a href="documento/{{$codigoInscricao}}/28" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td>1.2 Diploma da Graduação ou Certificado de Conclusão da Graduação<span class="text-danger">*</span> <br/> (é imprescindível a apresentação no ato da matrícula, em caso de aprovação do candidato)</td> 
            <td class="text-center">
                @if(!empty($diploma->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($diploma->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$diploma->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $diploma->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $diploma->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/6" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>1.3 Currículo Vittae/Lattes<span class="text-danger">*</span><br/> (anexar no currículo todos os documentos comprobatórios para avaliação conforme Edital)</td> 
            <td class="text-center">
                @if(!empty($curriculo->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($curriculo->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$curriculo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $curriculo->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $curriculo->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/9" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>

        <tr>
            <td>1.4 Histórico Escolar da Graduação<span class="text-danger">*</span><br/> (Histórico Escolar da Graduação concluída ou em andamento, bem como das transferências de cursos, se houver)</td> 
            <td class="text-center">
                @if(!empty($historico->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td class="text-center">
                @if (!empty($historico->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$historico->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $historico->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $historico->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/5" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>    
        @if ($pais != '')
            @if ($pais == 1)
                <tr>
                    <td>1.5a) CPF<span class="text-danger">*</span></td>   
                    <td class="text-center">
                        @if(!empty($cpf->codigoInscricaoArquivo))                            
                            <i class="fa fa-check text-success"></i>
                        @else
                            <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                        @endif
                    </td>
                    <td class="text-center">
                        @if (!empty($cpf->codigoArquivo) && empty($cpf->codigoInscricaoArquivo))
                            <a href="documento/{{ $cpf->codigoArquivo }}/anexar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Anexar arquivo a inscrição">
                                <i class="fas fa-paperclip"></i>
                            </a>  
                            
                            <a href="{{ asset('storage/'.$cpf->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        @if (!empty($cpf->codigoInscricaoArquivo))
                            <a href="{{ asset('storage/'.$cpf->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if ($status == 'N')
                                <a href="documento/{{ $cpf->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a href="documento/{{ $cpf->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif 
                        @else
                            <a href="documento/{{$codigoInscricao}}/1" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                                <i class="fa fa-plus"></i>
                            </a> 
                        @endif

                    </td>
                </tr>

                <tr>
                    <td>1.5b) RG<span class="text-danger">*</span></td> 
                    <td class="text-center">
                        @if(!empty($rg->codigoInscricaoArquivo))
                            <i class="fa fa-check text-success"></i>
                        @else
                            <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                        @endif   
                    </td>
                    <td class="text-center">
                        @if (!empty($rg->codigoArquivo) && empty($rg->codigoInscricaoArquivo))
                            <a href="documento/{{ $rg->codigoArquivo }}/anexar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Anexar arquivo a inscrição">
                                <i class="fas fa-paperclip"></i>
                            </a>  
                            
                            <a href="{{ asset('storage/'.$rg->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        @if (!empty($rg->codigoInscricaoArquivo))
                            <a href="{{ asset('storage/'.$rg->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if ($status == 'N')
                                <a href="documento/{{ $rg->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a href="documento/{{ $rg->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif 
                        @else
                            <a href="documento/{{$codigoInscricao}}/2" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                                <i class="fa fa-plus"></i>
                            </a> 
                        @endif
                    </td>
                </tr>

                @else
                <tr>
                    <td>1.5c) Passaporte<span class="text-danger">*</span></td> 
                    <td class="text-center">
                        @if(!empty($passaporte->codigoArquivo))
                            <i class="fa fa-check text-success"></i>
                        @else
                            <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                        @endif   
                    </td>
                    <td class="text-center">
                        @if (!empty($passaporte->codigoInscricaoArquivo))
                            <a href="{{ asset('storage/'.$passaporte->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if ($status == 'N')
                                <a href="documento/{{ $passaporte->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a href="documento/{{ $passaporte->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif 
                        @else
                            <a href="documento/{{$codigoInscricao}}/3" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                                <i class="fa fa-plus"></i>
                            </a> 
                        @endif
                    </td>
                </tr>
            @endif
        @endif
    </tbody> 
</table>