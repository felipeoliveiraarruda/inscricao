<!--
    Nivel
        1 Especial
        2 Direto
        3 Fluxo
        4 Mestrado
        5 Pae
        6 Proficiencia
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
    @if ($codigoEdital == 13)
        @include('inscricao.visualizar.mestrado.ppgpe')
    @elseif ($codigoEdital == 18)
        @include('inscricao.visualizar.mestrado.ppgmad')
    @else
        @include('inscricao.visualizar.mestrado.index')
    @endif
@endif

@if (Session::get('nivel') == 7)
    @include('inscricao.visualizar.mestrado.ppgem_estrangeiro')
@endif