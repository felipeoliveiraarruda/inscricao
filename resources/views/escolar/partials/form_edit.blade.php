<div class="form-group">  
    <label for="escolaResumoEscolar" class="font-weight-bold">Escola<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="escolaResumoEscolar" name="escolaResumoEscolar" value="{{ old('escolaResumoEscolar') ?? $escolar->escolaResumoEscolar ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <label for="especialidadeResumoEscolar" class="font-weight-bold">Título/Especialidade<span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="especialidadeResumoEscolar" name="especialidadeResumoEscolar" value="{{ old('especialidadeResumoEscolar') ?? $escolar->especialidadeResumoEscolar ?? "" }}" maxlength="255" required />
</div>

<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="inicioResumoEscolar" class="font-weight-bold">Início<span class="text-danger">*</span></label>
            @if (!empty($escolar->inicioResumoEscolar))
            <input type="date" step="1" class="form-control" id="inicioResumoEscolar" name="inicioResumoEscolar" value="{{ old('inicioResumoEscolar') ?? $escolar->inicioResumoEscolar->format('Y-m-d') ?? "" }}" required />
            @else
                <input type="date" step="1" class="form-control" id="inicioResumoEscolar" name="inicioResumoEscolar" value="{{ old('inicioResumoEscolar') ?? "" }}" />
            @endif 
        </div>

        <div class="col">
            <label for="finalResumoEscolar" class="font-weight-bold">Final</label>
            @if (!empty($escolar->finalResumoEscolar))
            <input type="date" step="1" class="form-control" id="finalResumoEscolar" name="finalResumoEscolar" value="{{ old('finalResumoEscolar') ?? $escolar->finalResumoEscolar->format('Y-m-d') ?? "" }}" required />
            @else
                <input type="date" step="1" class="form-control" id="finalResumoEscolar" name="finalResumoEscolar" value="{{ old('finalResumoEscolar') ?? "" }}" />
            @endif
        </div> 
    </div>
</div>

@if (empty($escolar->codigoInscricaoResumoEscolar))
<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="finalResumoEscolar" class="font-weight-bold">Selecione o Histórico Escolar</label>
            @php
                $arquivos = \App\Models\Arquivo::obterArquivosHistorico($codigoInscricao, true);
            @endphp

            @if(count($arquivos) > 1))
            <div class="table-responsive">
                <table class="table">
                    @foreach($arquivos as $arquivo)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arquivoHistorico" id="arquivoHistorico{{ $arquivo->codigoArquivo }}" value="{{ $arquivo->codigoArquivo }}">
                                <label class="form-check-label" for="arquivoHistorico{{ $arquivo->codigoArquivo }}">{{ $arquivo->tipoDocumento }}</label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>                                                       
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @else
                @php
                    $historico = $arquivos[0];
                @endphp
                <label for="codigoTipoDocumento" class="font-weight-bold">Histórico Escolar</label>
                <div class="form-group">
                    <input type="file" class="form-control-file" id="historicoEscolar" name="historicoEscolar" data-show-upload="false" data-show-caption="true" accept="application/pdf">
                </div>
                <div class="form-group">
                    Arquivo Atual : <a href="storage/{{ $historico->linkArquivo }}" target="_new">Visualizar</a>
                    <input type="hidden" name="arquivoAtualHistorico" value="{{ $historico->linkArquivo }}">
                </div>
            @endif
            <small id="arquivoHelp" class="form-text text-muted">Cópia digital do Histórico Escolar da Graduação conforme item 3.1.7 do edital no formato PDF</small><br/>
        </div>
    
        <div class="col">            
            <div class="form-group">
                <label for="finalResumoEscolar" class="font-weight-bold">Selecione o Diploma / Certificado</label>
                @php
                    $arquivos = \App\Models\Arquivo::obterArquivosDiploma($codigoInscricao, true);
                @endphp

                @if(count($arquivos) > 1))
                <div class="table-responsive">
                    <table class="table">
                        @foreach($arquivos as $arquivo)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="arquivoDiploma" id="arquivoDiploma{{ $arquivo->codigoArquivo }}" value="{{ $arquivo->codigoArquivo }}">
                                    <label class="form-check-label" for="arquivoDiploma{{ $arquivo->codigoArquivo }}">{{ $arquivo->tipoDocumento }}</label>
                                </div>
                            </td>
                            <td>
                                <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>                                                       
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @else
                    <div class="form-group">
                        <label for="codigoTipoDocumento" class="font-weight-bold">Diploma / Certificado</label><br/>
                        
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineDocumentos" id="inlineDocumentosDiploma" value="6">
                            <label class="form-check-label" for="inlineDocumentos1">Diploma</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineDocumentos" id="inlineDocumentosCertificado" value="7">
                            <label class="form-check-label" for="inlineDocumentos2">Certificado de Conclusão</label>
                        </div>

                        <input type="file" class="form-control-file mt-2" id="diplomaEscolar" name="diplomaEscolar" data-show-upload="false" data-show-caption="true" accept="application/pdf">
                    </div>
                    @if (!empty($codigoResumoEscolar))
                        <div class="form-group">
                            Arquivo Atual ({{ $diploma->tipoDocumento }}): <a href="storage/{{ $diploma->linkArquivo }}" target="_new">Visualizar</a>
                            <input type="hidden" name="arquivoAtualDiploma" value="{{ $diploma->linkArquivo }}">
                        </div>
                    @endif
                @endif
                <small id="arquivoHelp" class="form-text text-muted">Cópia digital (frente/verso) do Diploma ou Declaração de Conclusão do curso de graduação, contendo a data de colação de grau, conforme item 3.1.7 do edital no formato PDF</small>
            </div>
        </div>
    </div>    
</div>
@else
<div class="form-group">  
    <div class="form-row">
        <div class="col">
            <label for="finalResumoEscolar" class="font-weight-bold">Selecione o Histórico Escolar</label>
            @php
                $historico = \App\Models\Arquivo::obterArquivosHistorico($codigoInscricao, false, $codigoHistorico);
            @endphp

            <label for="codigoTipoDocumento" class="font-weight-bold">Histórico Escolar</label>
            <div class="form-group">
                <input type="file" class="form-control-file" id="historicoEscolar" name="historicoEscolar" data-show-upload="false" data-show-caption="true" accept="application/pdf">
            </div>
            <div class="form-group">
                Arquivo Atual : <a href="storage/{{ $historico->linkArquivo }}" target="_new">Visualizar</a>
                <input type="hidden" name="arquivoAtualHistorico" value="{{ $historico->linkArquivo }}">
                <input type="hidden" name="arquivoHistorico" value="{{ $historico->codigoArquivo }}">
            </div>
            <small id="arquivoHelp" class="form-text text-muted">Cópia digital do Histórico Escolar da Graduação conforme item 3.1.7 do edital no formato PDF</small><br/>
        </div>
    
        <div class="col">            
            <div class="form-group">
                <label for="finalResumoEscolar" class="font-weight-bold">Selecione o Diploma / Certificado</label>
                @php
                    $diploma = \App\Models\Arquivo::obterArquivosDiploma($codigoInscricao, false, $codigoDiploma);
                @endphp


                <div class="form-group">
                    <label for="codigoTipoDocumento" class="font-weight-bold">Diploma / Certificado</label><br/>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineDocumentos" id="inlineDocumentosDiploma" value="6">
                        <label class="form-check-label" for="inlineDocumentos1">Diploma</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineDocumentos" id="inlineDocumentosCertificado" value="7">
                        <label class="form-check-label" for="inlineDocumentos2">Certificado de Conclusão</label>
                    </div>

                    <input type="file" class="form-control-file mt-2" id="diplomaEscolar" name="diplomaEscolar" data-show-upload="false" data-show-caption="true" accept="application/pdf">
                </div>
                @if (!empty($diploma))
                    <div class="form-group">
                        Arquivo Atual ({{ $diploma->tipoDocumento }}): <a href="storage/{{ $diploma->linkArquivo }}" target="_new">Visualizar</a>
                        <input type="hidden" name="arquivoAtualDiploma" value="{{ $diploma->linkArquivo }}">
                        <input type="hidden" name="arquivoDiploma" value="{{ $diploma->codigoArquivo }}">
                    </div>
                @endif

                <small id="arquivoHelp" class="form-text text-muted">Cópia digital (frente/verso) do Diploma ou Declaração de Conclusão do curso de graduação, contendo a data de colação de grau, conforme item 3.1.7 do edital no formato PDF</small>
            </div>
        </div>
    </div>    
</div>

@endif

<input type="hidden" name="codigoInscricao" value="{{ $codigoInscricao }}">
<input type="hidden" name="codigoInscricaoResumoEscolar" value="{{ $codigoInscricaoResumoEscolar }}">
<input type="hidden" name="codigoResumoEscolar" value="{{ $codigoResumoEscolar }}">