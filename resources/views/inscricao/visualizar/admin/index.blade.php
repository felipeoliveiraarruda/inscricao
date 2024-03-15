@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('inscricao.visualizar.admin.menu')  
        </div>
        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Documentos Obrigatórios</h5>
               
  
                <div class="card-body">                    
                    <div class="row justify-content-center">
                        <table class="table table-striped">
                            @foreach($arquivos as $arquivo)
                            <tr>
                                <td>{{ $arquivo->ordemTipoDocumento}} {{ $arquivo->tipoDocumento }}</td>
                                <td class="text-center">            
                                    <a href="{{ asset('storage/'.$arquivo->linkArquivo) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" data-toggle="tooltip" data-placement="bottom" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="row justify-content-center">
                        @if ($status == 'P')
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                                            
                            <form id="formEnviar" class="needs-validation" novalidate method="POST" action="inscricao/validar/{{ $codigoInscricao }}">                                    
                                @csrf                   
                                <button type="submit" class="btn btn-primary btn-sm float-right" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Validar Inscrição</button>
                            </form>

                            <!-- Modal -->
                            @include('utils.loader')                        
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection