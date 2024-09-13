<table class="table">
    <thead>
        <tr>
            <th scope="col-5">#</th>
            <th scope="col">Status<span class="text-danger">*</span></th>

            @if ($codigoTipoDocumento == 24 || $codigoTipoDocumento == 25)
                <th scope="col">Quantidade (em meses)<span class="text-danger">*</span></th>
            @else
                <th scope="col">Quantidade<span class="text-danger">*</span></th>
            @endif
            <th scope="col">Tipo</th>
            <th scope="col">Justificativa</th>
        </tr>
    </thead>
    @foreach($arquivos as $arquivo)
        @php
            $analise   = App\Models\PAE\AnaliseCurriculo::obterAnalise($arquivo->codigoPae, $arquivo->codigoArquivo);
            $avaliacao = App\Models\PAE\Avaliacao::obterAvaliacao($arquivo->codigoPae, $arquivo->codigoTipoDocumento);
        @endphp
    <tr>
        <td>
            <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" target="_new" title="Visualizar">
                <i class="far fa-eye"></i>
            </a>
        </td>
        <td> 
            <select class="form-control" id="aceitarDocumento[{{ $analise->codigoAnaliseCurriculo }}]" name="aceitarDocumento[{{ $analise->codigoAnaliseCurriculo }}]" required>
                <option value="">Selecione uma opção</option>
                <option value="S|{{ $analise->codigoAnaliseCurriculo }}" @if ($analise->statusAnaliseCurriculo == 'S') selected @endif>Aceitar</option>
                <option value="N|{{ $analise->codigoAnaliseCurriculo }}" @if ($analise->statusAnaliseCurriculo == 'N') selected @endif>Alterar</option>
                <option value="R|{{ $analise->codigoAnaliseCurriculo }}" @if ($analise->statusAnaliseCurriculo == 'R') selected @endif>Rejeitar</option>
            </select>         
        </td>
        <td>
            <input type="number" class="form-control" id="pontuacaoAnalise[{{ $analise->codigoAnaliseCurriculo }}]" name="pontuacaoAnalise[{{ $analise->codigoAnaliseCurriculo }}]" value="{{ $avaliacao->pontuacaoAvaliacao ??  1 }}">
        </td>
        <td> 
            <select class="form-control" id="tipoDocumentoAnalise[{{ $analise->codigoAnaliseCurriculo }}]" name="tipoDocumentoAnalise[{{ $analise->codigoAnaliseCurriculo }}]" @if ($analise->statusAnaliseCurriculo == 'S') disabled @endif>
                <option value="">Selecione uma opção</option>
                @foreach ($tipos as $tipo)
                    @if($arquivo->codigoTipoDocumento != $tipo->codigoTipoDocumento)
                        <option value="{{ $tipo->codigoTipoDocumento }}">{{ $tipo->tipoDocumento }}</option>
                    @endif
                @endforeach
            </select>          
        </td>
        <td>
            <input type="text" class="form-control" id="justificativaAnalise[{{ $analise->codigoAnaliseCurriculo }}]" name="justificativaAnalise[{{ $analise->codigoAnaliseCurriculo }}]" maxlength="255" @if ($analise->statusAnaliseCurriculo == 'S') disabled  @endif  value="{{ $analise->justificativaAnaliseCurriculo ??  '' }}"/>          
            <input type="hidden" name="codigoAnaliseCurriculo[]" value="{{ $analise->codigoAnaliseCurriculo }}">
        </td>
    </tr>
    @endforeach
</table>


<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
<input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
<input type="hidden" name="codigoTipoDocumento" value="{{ $codigoTipoDocumento }}">