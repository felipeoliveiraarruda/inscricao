<!DOCTYPE html>
<html>
<head>
    <title>REQUERIMENTO DE INSCRIÇÃO</title>
</head>
<body>
 
<h4>NÚMERO DE INSCRIÇÃO: {{ $inscricao }} - {{ $anosemestre }}</h4>
 
<p>O(a) candidato(a) {{ $nome }} finalizou a inscrição no processo seletivo {{ $sigla }} {{ $edital->descricaoNivel }} - {{ $anosemestre }}.</p>

<p>Acesse o sistema de inscrição para fazer a validação do candidato.</p>

<p><a href="https://inscricao.eel.usp.br/">https://inscricao.eel.usp.br/</a></p>
 
</body>
</html>