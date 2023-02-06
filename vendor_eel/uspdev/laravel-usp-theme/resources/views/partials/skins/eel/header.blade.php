@section('skin_styles')
@parent {{-- devemos incluir o conteúdo existente --}}
<style>
    /* #skin_header é o div pai */
    #skin_header {
        background-color: #26385C;
    }

    #skin_header  .container-fluid {
        display: block;
        height: 70px;
        background-color: #26385C;
        font-size: 20px;
    }

    #skin_header .skin_logo img {
        height: 50px;
        margin: 10px;
    }

    #skin_header .skin_texto img {
        margin-top: 8px;
        height: 50px;
    }

    .navbar {

        background-color: #26385C;
    }

</style>
@endsection

@section('skin_header')
<!-- container vai ocultar em mobile para ganhar espaço -->
<div class="container-fluid d-none d-sm-block">
    <div class="row">
        <div class="col-md-12">
            <a class="skin_logo" href="https://www.eel.usp.br/">
                <img src="{{ asset('/vendor/laravel-usp-theme/skins/eel/images/logo_eel_2017.png') }}" alt="EEL - Escola de Engenharia de Lorena" />
            </a>
            <div class="float-right text-right pr-1 pt-2 text-white">
                <div>Sistemas de Inscrições</div>
                <div class="small">Escola de Engenharia de Lorena</div>
            </div>
        </div>
    </div>
</div>

@endsection