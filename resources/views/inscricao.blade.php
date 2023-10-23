@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Inscrição</h5>
                
                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <div class="col-md-5 text-center">                            
                            <div class="btn-group btn-group-sm" role="group" aria-label="Status">
                                <button type="button" class="btn @if ($status == 'N') btn-success @else btn-secondary @endif">Aberta</button>
                                <button type="button" class="btn @if ($status == 'P') btn-success @else btn-secondary @endif">Pendente</button>
                                <button type="button" class="btn @if ($status == 'C') btn-success @else btn-secondary @endif">Confirmada</button>
                            </div> 
                            @if ($status == 'P')
                                <hr></hr>
                            @elseif ($status == 'N')
                                <hr></hr>
                            @elseif ($status == 'C')
                                <p class="text-center">Sua inscrição para esse processo seletivo já está confirmada.</p>
                                <p class="text-center">Aguarde mais informações no seu e-mail.</p>
                                <a href="/" target="_new" class="btn btn-info btn-block" role="button" aria-pressed="true">Voltar</a>
                            @endif
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection