<!--
    Nivel
        1 Especial
        2 Direto
        3 Fluxo
        4 Mestrado
        5 Pae
-->
@if (Session::get('nivel') == 1)
    @include('inscricao.visualizar.especial.index')
@endif

@if (Session::get('nivel') == 2)
    @include('inscricao.visualizar.doutorado.index')
@endif

@if (Session::get('nivel') == 3)
    @include('inscricao.visualizar.fluxo.index')
@endif

@if (Session::get('nivel') == 4)
    @include('inscricao.visualizar.mestrado.index')
@endif