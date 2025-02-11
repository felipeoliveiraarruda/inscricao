@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <img width="300" height="auto" src="/images/logo-ppgem.png">
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Egressos</h5>
                
                <div class="card-body">                                        
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                                </p>
                            @endif
                        @endforeach
                    </div>

                    <h4 class="text-justify mb-2">Você já se cadastrou no ALUMNI USP? A base de Egressos da USP.</h4>

                    <h4 class="text-justify mb-2">Se não, acesse: <a href="https://www.alumni.usp.br/">https://www.alumni.usp.br/</a></h4>

                    <img width="915px" height="auto" src="/images/alumni.png">
                </div>
            </div>
        </div>
    </div>
</main>
@endsection