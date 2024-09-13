<table class="table">
    <thead>
        <tr>
            <th scope="col">Conceito</th>
            <th scope="col">Quantidade</th>
        </tr>
    </thead> 
    @foreach($conceitos as $conceito)
        @if ($conceito->codigoDesempenhoAcademico)
            <tr>
                <td>{{ $conceito->descricaoConceito }}</td>
                <td>
                    <input type="number" class="form-control" id="quantidadeDesempenhoAcademico" name="quantidadeDesempenhoAcademico[{{ $conceito->codigoDesempenhoAcademico }}]" value="{{ old('quantidadeDesempenhoAcademico') ?? $conceito->quantidadeDesempenhoAcademico ??  '' }}">
                    <input type="hidden" name="codigoDesempenhoAcademico[{{ $conceito->codigoDesempenhoAcademico }}]" value="{{ $conceito->codigoDesempenhoAcademico }}">
                </td> 
            </tr>
        @else
            @if(App\Models\PAE\DesempenhoAcademico::obterDesempenho($codigoPae, $conceito->codigoConceito) == '')                
            <tr>
                <td>{{ $conceito->descricaoConceito }}</td>
                <td>
                    <input type="number" class="form-control" id="quantidadeDesempenhoAcademico" name="quantidadeDesempenhoAcademico[{{ $conceito->codigoConceito }}]" value="{{ old('quantidadeDesempenhoAcademico') ?? '' }}">
                    <input type="hidden" name="codigoConceito[{{ $conceito->codigoConceito }}]" value="{{ $conceito->codigoConceito }}">
                </td>
            </tr>
            @endif
        @endif
    @endforeach
</table>

<input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">