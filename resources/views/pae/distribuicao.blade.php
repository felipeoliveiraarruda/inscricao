@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">        
                <a href="admin" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        @if ($msg == 'success')
                        <div class="alert alert-success" id="success-alert">
                            {{ Session::get('alert-' . $msg) }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @else
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
                        </p>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="card bg-default">
                <h5 class="card-header">PAE - Processo Seletivo - Distribuição de Avaliadores - {{ $anosemestre }}</h5>
                
                <div class="card-body">                    
                    <div class="row">
                        <div class="col"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Total de Inscritos</th>
                                        <th scope="col">Total de Avaliadores</th>
                                        <th scope="col">Distribuição</th>
                                    </tr>
                                </thead>
                                    <tr>
                                        <td>{{ $confirmados }}</td>
                                        <td>{{ $avaliadores }}</td>
                                        <td>{{ $total }} por avaliador</td>
                                    </tr>
                            </table>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col"> 
							<form id="formEnviar" class="needs-validation" novalidate method="POST" action="admin/{{ $codigoEdital }}/pae/distribuicao/store">
								@csrf                   
                               
                                <input type="hidden" name="codigoEdital" value="{{ $codigoEdital }}">
								
                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Distribuir Avaliações</button>
							</form>
                        </div>
                    </div>  
                    
                    <!-- Modal -->
                    <div class="modal fade" id="loaderModal" tabindex="-1" aria-labelledby="loaderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">		
                                <div class="modal-body text-justify">
                                    <div id="loader">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-danger" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-warning" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-info" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-light" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <div class="spinner-grow text-dark" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <!-- Modal -->               
                </div>
            </div>
        </div>
    </div>
</main>

@endsection