<div class="list-group">
    <a href="inscricao/{{ $codigoInscricao }}/pessoal" class="list-group-item list-group-item-action">
        1.Dados Pessoais 
        <i class="fa @if (Session::get('total')['pessoal'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/endereco" class="list-group-item list-group-item-action">
        1.1Endereço
        <i class="fa @if (Session::get('total')['endereco'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/emergencia" class="list-group-item list-group-item-action">
        2.Emergência
        <i class="fa @if (Session::get('total')['emergencia'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/escolar" class="list-group-item list-group-item-action">3.Resumo Escolar
        <i class="fa @if (Session::get('total')['escolar'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/idioma" class="list-group-item list-group-item-action">4.Idiomas
        <i class="fa @if (Session::get('total')['idioma'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/profissional" class="list-group-item list-group-item-action">5.Experiência Profissional
        <i class="fa @if (Session::get('total')['profissional'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>
    <a href="inscricao/{{ $codigoInscricao }}/ensino" class="list-group-item list-group-item-action">6.Experiência Em Ensino
        <i class="fa @if (Session::get('total')['ensino'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
    </a>         
    <a href="inscricao/{{ $codigoInscricao }}/financeiro" class="list-group-item list-group-item-action">7.Recursos Financeiros
        <i class="fa @if (Session::get('total')['financeiro'] > 0) fa-check text-success @else fa-exclamation-triangle text-warning @endif float-right"></i>
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