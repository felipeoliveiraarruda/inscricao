@if (Session::get('total')['especial'] >= 6 && $total >= 11 && !empty($requerimento) && $status == 'N') 
    <div class="col-sm-12 mb-4">  
        <!-- Validation Errors -->
        <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />

        <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/{{ $codigoInscricao }}/requerimento/store" enctype="multipart/form-data"> 
            @csrf
                                                            
            <input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">                        
            <input type="hidden" name="codigoTipo" value="ppgem">

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
            <td>4.1 Foto</td>
            <td class="text-center">
                @if(!empty($foto->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif
            </td>
            <td class="text-center">            
                @if (!empty($foto->codigoArquivo) && empty($foto->codigoInscricaoArquivo))
                    <a href="imagem/{{ $foto->codigoArquivo }}/anexar/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="bottom" title="Anexar arquivo a inscrição">
                        <i class="fas fa-paperclip"></i>
                    </a>  
                    
                    <a href="{{ asset('storage/'.$foto->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>
                @endif

                @if (!empty($foto->codigoInscricaoArquivo))

                    <a href="{{ asset('storage/'.$foto->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="imagem/{{ $foto->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="imagem/{{ $foto->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="imagem/{{$codigoInscricao}}/27" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>4.2 Ficha de Inscrição            
                @if (Session::get('total')['especial'] >= 6 && $total >= 11)     
                 - <a href="inscricao/comprovante/{{ $codigoInscricao }}" target="_new">Imprimir</a>
                @else
                
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
                    @if (Session::get('total')['especial'] >= 6 && $total >= 11) 
                    <a href="documento/{{$codigoInscricao}}/28" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td>4.3a) CPF</td>   
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
                    <a href="documento/{{$codigoInscricao}}/2" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif

            </td>
        </tr>
        <tr>
            <td>4.3b) RG</td> 
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
                    
                    <a href="{{ asset('storage/'.$cpf->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
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
        <tr>
            <td>4.3c) RNE</td> 
            <td class="text-center">
                @if(!empty($rne->codigoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif   
            </td>
            <td class="text-center">
                @if (!empty($rne->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$rg->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $rne->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $rne->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/4" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>4.3d) Passaporte</td> 
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
        <tr>
            <td>4.4 Histórico Escolar do Mestrado</td> 
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
                    <a href="documento/{{$codigoInscricao}}/29" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>4.5 Diploma de Mestrado ou Declaração de homologação da dissertação de Mestrado</td> 
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
                    <a href="documento/{{$codigoInscricao}}/30" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>4.6 Currículo</td> 
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
            <td>4.7. Plano de Estudos - <a href="https://cpg.eel.usp.br/aluno/formularios/plano-de-estudo" target="_new">Modelo</a></td> 
            <td class="text-center">
                @if(!empty($plano_estudo->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($plano_estudo->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$plano_estudo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $plano_estudo->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $plano_estudo->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/31" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>
        </tr>
        <tr>
            <td>4.8. Projeto de Pesquisa</td> 
            <td class="text-center">
                @if(!empty($projeto->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($projeto->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$projeto->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $plano_estudo->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $plano_estudo->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/32" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>
        </tr>
        <tr>
            <td>4.9. Carta digital de encaminhamento do futuro orientador</td> 
            <td class="text-center">
                @if(!empty($carta->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($carta->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$carta->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $carta->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $carta->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/33" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>
        </tr>
        <tr>
            <td>4.10 Currículo Lattes digital do futuro orientador</td> 
            <td class="text-center">
                @if(!empty($curriculo_orientador->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($curriculo_orientador->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$curriculo_orientador->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $curriculo_orientador->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $curriculo_orientador->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/34" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>            
        </tr>
        <tr>
            <td>4.11. Termo de Compromisso de Orientação - <a href="https://cpg.eel.usp.br/sites/files/cpg/arquivos/formularios/12_Termo_Compromisso_de_Orientacao.pdf" target="_new">Modelo</a></td> 
            <td class="text-center">
                @if(!empty($termo_orientacao->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($termo_orientacao->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$termo_orientacao->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $termo_orientacao->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $termo_orientacao->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/35" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>  
        </tr>
        @if ($coorientador == 'S')

        <tr>
            <td>4.12. Currículo Lattes digital do futuro coorientador</td> 
            <td class="text-center">
                @if(!empty($curriculo_coorientador->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($curriculo_coorientador->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$curriculo_coorientador->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $curriculo_coorientador->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $curriculo_coorientador->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/36" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>  
        </tr>
        <tr>
            <td>4.13. Formulário de Credenciamento do Coorientador - <a href="https://cpg.eel.usp.br/sites/files/cpg/arquivos/formulario_de_credendiamentorecredenciamento_de_orientadores.docx" target="_new">Modelo</a></td> 
            <td class="text-center">
                @if(!empty($credenciamento->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($credenciamento->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$credenciamento->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $credenciamento->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $credenciamento->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/37" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>  
        </tr>
        <tr>
            <td>4.14. Carta de Aceite do Orientador</td> 
            <td class="text-center">
                @if(!empty($carta_aceite->codigoInscricaoArquivo))
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-exclamation-triangle text-warning"></i>                                  
                @endif 
            </td>
            <td class="text-center">
                @if (!empty($carta_aceite->codigoInscricaoArquivo))
                    <a href="{{ asset('storage/'.$carta_aceite->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if ($status == 'N')
                        <a href="documento/{{ $carta_aceite->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $carta_aceite->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif 
                @else
                    <a href="documento/{{$codigoInscricao}}/38" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif                
            </td>  
        </tr>
        @endif
    </tbody> 
</table>