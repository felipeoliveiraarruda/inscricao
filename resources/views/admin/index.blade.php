@extends('layouts.app')

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">        
        <div class="col-md-3">
            @include('admin.menu.list')           
        </div> 
        
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Administração</h5>
                
                <div class="card-body">
               
                </div>
            </div>
        </div>
    </div>
</main>

@endsection