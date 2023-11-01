<table class="table">
    <thead>
        <tr>
            <th scope="col">Conceito</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Pontuação</th>
        </tr>
    </thead> 
    @foreach($conceitos as $conceito)  
        @php
            $dados = App\Models\PAE\DesempenhoAcademico::obterDesempenho($codigoPae, $conceito->codigoConceito);
        @endphp
        @if($dados == '')
            <tr>
                <td>{{ $conceito->descricaoConceito }}</td>
                <td>
                    <input type="number" class="form-control" id="quantidadeDesempenhoAcademico" name="quantidadeDesempenhoAcademico[{{ $conceito->codigoConceito }}]" value="{{ old('quantidadeDesempenhoAcademico') ?? '' }}">
                    <input type="hidden" name="codigoConceito[{{ $conceito->codigoConceito }}]" value="{{ $conceito->codigoConceito }}">
                </td>
                <td></td>
            </tr>
        @else
            <tr>
                <td>{{ $conceito->descricaoConceito }}</td>
                <td>
                    <input type="number" class="form-control" id="quantidadeDesempenhoAcademico" name="quantidadeDesempenhoAcademico[{{ $dados->codigoDesempenhoAcademico }}]" value="{{ old('quantidadeDesempenhoAcademico') ?? $dados->quantidadeDesempenhoAcademico ??  '' }}">
                    <input type="hidden" name="codigoDesempenhoAcademico[{{ $dados->codigoDesempenhoAcademico }}]" value="{{ $dados->codigoDesempenhoAcademico }}">
                </td> 
                <td>{{ number_format($dados->totalDesempenhoAcademico, 2, ',', '') }}</td>
            </tr>
        @endif
    @endforeach
</table>

<input type="hidden" name="codigoPae" value="{{ $codigoPae }}">
<input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">