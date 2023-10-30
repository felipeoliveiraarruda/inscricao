<table class="table">
    <thead>
        <tr>
            <th scope="col-5">#</th>
            <th scope="col">Status<span class="text-danger">*</span></th>
            <th scope="col">Pontuação<span class="text-danger">*</span></th>
            <th scope="col">Tipo</th>
            <th scope="col">Justificativa</th>
        </tr>
    </thead>
@foreach($arquivos as $arquivo)
    <tr>
        <td>
            <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-info btn-sm" target="_new" title="Visualizar">
                <i class="far fa-eye"></i>
            </a>
        </td>
        <td> 
            <select class="form-control" id="aceitarDocumento[{{ $arquivo->codigoArquivo }}]" name="aceitarDocumento[{{ $arquivo->codigoArquivo }}]" required>
                <option value="">Selecione uma opção</option>
                <option value="S|{{ $arquivo->codigoArquivo }}" selected>Aceitar</option>
                <option value="N|{{ $arquivo->codigoArquivo }}">Alterar</option>
                <option value="R|{{ $arquivo->codigoArquivo }}">Rejeitar</option>
            </select>         
        </td>
        <td>
            <input type="number" class="form-control" id="pontuacaoAnalise[{{ $arquivo->codigoArquivo }}]" name="pontuacaoAnalise[{{ $arquivo->codigoArquivo }}]" value="{{ old("pontuacaoAnaliseCurriculo[{$arquivo->codigoArquivo}]") ??  '1' }}">
        </td>
        <td> 
            <select class="form-control" id="tipoDocumentoAnalise[{{ $arquivo->codigoArquivo }}]" name="tipoDocumentoAnalise[{{ $arquivo->codigoArquivo }}]" disabled>
                <option value="">Selecione uma opção</option>
                @foreach ($tipos as $tipo)
                    @if($arquivo->codigoTipoDocumento != $tipo->codigoTipoDocumento)
                        <option value="{{ $tipo->codigoTipoDocumento }}">{{ $tipo->tipoDocumento }}</option>
                    @endif
                @endforeach
            </select>          
        </td>
        <td>
            <input type="text" class="form-control" id="justificativaAnalise[{{ $arquivo->codigoArquivo }}]" name="justificativaAnalise[{{ $arquivo->codigoArquivo }}]" maxlength="255" disabled />          
        </td>
    </tr>
 @endforeach
</table>

<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
<input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
<input type="hidden" name="codigoTipoDocumento" value="{{ $codigoTipoDocumento }}">