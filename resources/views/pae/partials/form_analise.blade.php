<table class="table">
    <thead>
        <tr>
            <th scope="col">Item Avaliado</th>
            <th scope="col">Pontuação</th>
        </tr>
    </thead>
    @foreach($tipos as $tipo)
        @if ($tipo->codigoAnaliseCurriculo)
            <tr>
                @if ($tipo->maximoTipoAnalise > 0)
                    <td>{{ $tipo->tipoAnalise }} (colocar quantidade de meses)</td>
                @else
                    <td>{{ $tipo->tipoAnalise }}</td>
                @endif
                <td>
                    <input type="number" class="form-control" id="pontuacaoAnaliseCurriculo" name="pontuacaoAnaliseCurriculo[{{ $tipo->codigoAnaliseCurriculo }}]" value="{{ old('pontuacaoAnaliseCurriculo') ?? $tipo->pontuacaoAnaliseCurriculo ??  '' }}">
                    <input type="hidden" name="codigoAnaliseCurriculo[{{ $tipo->codigoAnaliseCurriculo }}]" value="{{ $tipo->codigoAnaliseCurriculo }}">
                </td>
            </tr>
        @else
            @if(App\Models\PAE\AnaliseCurriculo::obterAnalise($codigoPae, $tipo->codigoTipoAnalise) == '')  
                <tr>
                    @if ($tipo->maximoTipoAnalise > 0)
                    <td>{{ $tipo->tipoAnalise }} (colocar quantidade de meses)</td>
                    @else
                    <td>{{ $tipo->tipoAnalise }}</td>
                    @endif
                    <td>
                        <input type="number" class="form-control" id="pontuacaoAnaliseCurriculo" name="pontuacaoAnaliseCurriculo[{{ $tipo->codigoTipoAnalise }}]" value="{{ old('pontuacaoAnaliseCurriculo') ??  '' }}">
                        <input type="hidden" name="codigoTipoAnalise[{{ $tipo->codigoTipoAnalise }}]" value="{{ $tipo->codigoTipoAnalise }}">
                    </td>
                </tr>
            @endif
        @endif
    @endforeach
</table>

<input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">