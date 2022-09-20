@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Entrar</h5>
                
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</main>

@endsection