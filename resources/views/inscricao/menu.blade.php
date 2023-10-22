<div class="list-group">
    <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action">
        Dados Pessoais 
        <i class="fa @if (Session::get('total')['pessoal'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/endereco" class="list-group-item list-group-item-action">
        Endereço
        <i class="fa @if (Session::get('total')['endereco'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/emergencia" class="list-group-item list-group-item-action">
        Emergência
        <i class="fa @if (Session::get('total')['emergencia'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/escolar" class="list-group-item list-group-item-action">Resumo Escolar
        <i class="fa @if (Session::get('total')['escolar'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/idioma" class="list-group-item list-group-item-action">Idiomas
        <i class="fa @if (Session::get('total')['idioma'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/profissional" class="list-group-item list-group-item-action">Experiência Profissional
        <i class="fa @if (Session::get('total')['profissional'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/ensino" class="list-group-item list-group-item-action">Experiência Em Ensino
        <i class="fa @if (Session::get('total')['ensino'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>         


    <!--
    <a href="#" class="list-group-item list-group-item-action">Resumo Escolar<i class="fa fa-exclamation-triangle text-warning float-right"></i></a>
    <a href="#" class="list-group-item list-group-item-action">Idioma<i class="fa fa-exclamation-triangle text-warning float-right"></i></a>
    <a href="#" class="list-group-item list-group-item-action">Experiencia Profissional<i class="fa fa-exclamation-triangle text-warning float-right"></i></a>
    <a href="#" class="list-group-item list-group-item-action">Experiencia Em Ensino<i class="fa fa-exclamation-triangle text-warning float-right"></i></a>
    <a href="#" class="list-group-item list-group-item-action">
        Anexos
    </a>-->
</div>