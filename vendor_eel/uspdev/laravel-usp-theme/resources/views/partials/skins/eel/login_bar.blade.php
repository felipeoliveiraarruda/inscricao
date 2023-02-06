@section('skin_styles')
@parent {{-- devemos incluir o conteúdo existente --}}
<style>
    /* #skin_login_bar é o div pai */
    #skin_login_bar {
        display: block;
        /*background-image: url("{{ asset('/vendor/laravel-usp-theme/skins/uspdev/images/bg-headtop.gif') }}");*/
        /*background-color: #26385C;*/
        font-size: 15px;
        padding: 10px;
        margin-bottom: 10px;
    }

    /* .login_logout_link formata os links correspondentes que estão nos includes */
    #skin_login_bar .login_logout_link {
        /*color: #FFFFFF !important;*/
        text-decoration: none !important;
        font-weight: bold;
        padding-left: 5px;
        padding-right: 10px;
    }

</style>
@endsection

@section('skin_login_bar')
{{-- esta faixa está fora de container para tocar as bordas da janela --}}
<div class="text-right align-middle">
    @auth
        {{ Auth::user()->name }} - {{ Auth::user()->email }} |
        @include('laravel-usp-theme::partials.login_bar.logout_link')
    @else
        @include('laravel-usp-theme::partials.login_bar.login_link')
    @endauth
</div>
@endsection
