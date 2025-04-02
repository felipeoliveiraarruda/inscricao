@if (!empty($idiomas))          
<div class="table-responsive">                          
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Idioma</th>
                <th scope="col">Leitura</th>
                <th scope="col">Redação</th>
                <th scope="col">Conversação</th>
                <th scope="col"></th>
            </tr>
        </thead>
        @foreach($idiomas as $idioma)
        <tr>
            <td>{{ $idioma->descricaoIdioma }}</td>
            <td>{{ $idioma->leituraIdioma }}</td>
            <td>{{ $idioma->redacaoIdioma }}</td>
            <td>{{ $idioma->conversacaoIdioma }}</td>
            <td></td>
        </tr>
        @endforeach
    </table>                                  
    @endif   
</div>