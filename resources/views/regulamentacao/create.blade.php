@extends('layouts.app')

@auth
    @php redirect()->intended('index'); @endphp
@endauth

@section('content')
<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-3">
            <div class="list-group">
                <a href="dashboard" class="list-group-item list-group-item-action ">Voltar</a>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Formulário Regulamentação</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="regulamentacao/store" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col">   
                                    <p><b>DECLARO</b> para os devidos fins que</p>
                                
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="opcaoResolucao" id="opcaoResolucao1" value="S" required>
                                        <label class="form-check-label" for="opcaoResolucao1">Opto</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="opcaoResolucao" id="opcaoResolucao2" value="N" required>
                                        <label class="form-check-label" for="opcaoResolucao2">Não Opto</label>
                                    </div>

                                    <p class="mt-3">pelo {!! $regulamento->textoRegulamento !!}</p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="codigoRegulamento" value="{{ $regulamento->codigoRegulamento }}">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection



