@if (Session::get('total')['mestrado'] >= 4 && $total >= 11 && !empty($requerimento) && $status == 'N')
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
            <td>4.1 Foto (Somente arquivos de imagem como png, jpg, bmp)<span class="text-danger">*</span></td>
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
            <td>4.2 Ficha de Inscrição (Arquivo será gerado após a submissão dos documento obrigatórios)<span class="text-danger">*</span>            
                @if (Session::get('total')['mestrado'] >= 3 && $total >= 6)     
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
                    @if (Session::get('total')['mestrado'] >= 3 && $total >= 6)  
                    <a href="documento/{{$codigoInscricao}}/28" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td>4.3c) Passaporte</td> 
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
                    <a href="documento/{{$codigoInscricao}}/4" role="button" aria-pressed="true" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Novo">
                        <i class="fa fa-plus"></i>
                    </a> 
                @endif
            </td>
        </tr>
        <tr>
            <td>4.4 Histórico Escolar da Graduação<br/> (Histórico Escolar da Graduação concluída ou em andamento, bem como das transferências de cursos, se houver)</td> 
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
        <tr>
            <td>4.5 Diploma da Graduação ou Certificado de Conclusão da Graduação<br/> (é imprescindível a apresentação no ato da matrícula, em caso de aprovação do candidato)</td> 
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
            <td>4.6 Currículo Lattes<span class="text-danger">*</span><br/> (anexar no currículo todos os documentos comprobatórios para avaliação conforme item 3.1.8 e 6.1 do Edital)</td> 
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
            <td>4.7 Plano de Estudos<span class="text-danger">*</span> - <a href="https://cpg.eel.usp.br/aluno/formularios/plano-de-estudo" target="_new">Modelo</a></td> 
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
            <td>4.8 Projeto de Pesquisa<span class="text-danger">*</span></td> 
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
                        <a href="documento/{{ $projeto->codigoArquivo }}/edit/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Alterar">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a href="documento/{{ $projeto->codigoArquivo }}/destroy/{{ $codigoInscricao }}" role="button" aria-pressed="true" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Apagar">
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
            <td>4.9 Carta digital de encaminhamento do futuro orientador<span class="text-danger">*</span></td> 
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
            <td>4.10 Termo de Compromisso de Orientação<span class="text-danger">*</span> - <a href="https://cpg.eel.usp.br/sites/files/cpg/arquivos/formularios/12_Termo_Compromisso_de_Orientacao.pdf" target="_new">Modelo</a></td> 
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
        <tr>
            <td>4.11 Currículo Lattes do orientador<span class="text-danger">*</span></td> 
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
    </tbody> 
</table>