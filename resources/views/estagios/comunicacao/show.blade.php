@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ url('/estagios/comunicacao/listar') }}" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Processo Seletivo Estágiário Comunicação 2025</h5>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">CPF</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Telefone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                                                
                                    <td>{{ $estagio->cpfEstagio }}</td>
                                    <td>{{ $estagio->nomeEstagio }}</td>
                                    <td>{{ $estagio->emailEstagio }}</td>
                                    <td>{{ $estagio->telefoneEstagio }}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Curso</th>
                                    <th scope="col" colspan="2">Semestre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                                                
                                    <td colspan="2">{{ $estagio->cursoEstagio }}</td>
                                    <td colspan="2">{{ $estagio->semestreEstagio }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="card bg-default">
                                <h5 class="card-header">Endereço</h5>
                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Logradrouro</th>
                                                    <th scope="col">Cidade/Uf</th>
                                                    <th scope="col">CEP</th>
                                                </tr>
                                            </thead>                                
                                            <tr>
                                                <td>{{ $estagio->logradouroEnderecoEstagio }}, {{ $estagio->numeroEnderecoEstagio }} {{ $estagio->bairroEnderecoEstagio }} {{ $estagio->complementoEnderecoEstagio }}</td>
                                                <td>{{ $estagio->localidadeEnderecoEstagio }}/{{ $estagio->ufEnderecoEstagio }}</td>
                                                <td>{{ $estagio->cepEnderecoEstagio }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="card bg-default">
                                <h5 class="card-header">Conhecimento</h5>
                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Facebook</th>
                                                    <th scope="col" class="text-center">Instagram</th>
                                                    <th scope="col" class="text-center">Twitter</th>
                                                    <th scope="col" class="text-center">Word</th>
                                                    <th scope="col" class="text-center">Excel</th>
                                                    <th scope="col" class="text-center">PowerPoint</th>
                                                    <th scope="col" class="text-center">Podcast</th>
                                                    <th scope="col" class="text-center">Doodle</th>
                                                </tr>
                                            </thead>                                
                                            <tr>
                                                <td class="text-center">{{ $estagio->facebookEstagio }}</td>
                                                <td class="text-center">{{ $estagio->instagramEstagio }}</td>
                                                <td class="text-center">{{ $estagio->twitterEstagio }}</td>
                                                <td class="text-center">{{ $estagio->wordEstagio }}</td>
                                                <td class="text-center">{{ $estagio->excelEstagio }}</td>
                                                <td class="text-center">{{ $estagio->powerPointEstagio }}</td>
                                                <td class="text-center">{{ $estagio->podcastEstagio }}</td>
                                                <td class="text-center">{{ $estagio->doodleEstagio }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($estagio->idiomasEstagio != '')                    
                    <div class="row mb-3">
                        <div class="col">
                            <div class="card bg-default">
                                <h5 class="card-header">Idiomas</h5>
                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Idioma</th>
                                                    <th scope="col">Leitura</th>
                                                    <th scope="col">Redação</th>
                                                    <th scope="col">Conversação</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            @php
                                                $idiomas = json_decode($estagio->idiomasEstagio);
                                            @endphp

                                            @foreach($idiomas as $idioma)
                                            <tr>
                                                <td>{{ $idioma->descricaoIdioma }}</td>
                                                <td>{{ $idioma->leituraIdioma }}</td>
                                                <td>{{ $idioma->redacaoIdioma }}</td>
                                                <td>{{ $idioma->conversacaoIdioma }}</td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </table> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col">
                            <div class="card bg-default">
                                <h5 class="card-header">Redes Sociais</h5>
                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Facebook</th>
                                                    <th scope="col" class="text-center">Instagram</th>
                                                    <th scope="col" class="text-center">Twitter</th>
                                                    <th scope="col" class="text-center">Linkedin</th>
                                                </tr>
                                            </thead>                                
                                            <tr>
                                                <td class="text-center">
                                                    @if($estagio->facebookTextEstagio != '')
                                                    <a href="{{ $estagio->facebookTextEstagio }}" target="_new" class="btn btn-primary" style="background-color: #3b5998;">
                                                        <i class="fab fa-facebook-f"></i> Visualizar
                                                    </a>
                                                    @else
                                                    -
                                                    @endif
                                                </td>                                                
                                                <td class="text-center">
                                                    @if($estagio->instagramTextEstagio != '')
                                                    <a href="{{ $estagio->instagramTextEstagio }}" target="_new" class="btn btn-primary" style="background-color: #3f729b;">
                                                        <i class="fab fa-instagram"></i> Visualizar
                                                    </a>
                                                    @else
                                                    -
                                                    @endif                                                    
                                                </td>
                                                <td class="text-center">
                                                    @if($estagio->twitterTextEstagio != '')
                                                    <a href="{{ $estagio->twitterTextEstagio }}" target="_new" class="btn btn-primary" style="background-color: #00acee;">
                                                        <i class="fab fa-twitter"></i> Visualizar
                                                    </a>
                                                    @else
                                                    -
                                                    @endif                                                    
                                                </td>
                                                <td class="text-center">
                                                    @if($estagio->linkedinTextEstagio != '')
                                                    <a href="{{ $estagio->linkedinTextEstagio }}" target="_new" class="btn btn-primary" style="background-color: #0e76a8;">
                                                        <i class="fab fa-linkedin"></i> Visualizar
                                                    </a> 
                                                    @else
                                                    -
                                                    @endif                                                     
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="card bg-default">
                                <h5 class="card-header">Currículo</h5>
                
                                <div class="card-body">
                                    @if ($estagio->curriculoEstagio != '')
                                    <a href="{{ asset('storage/'.$estagio->curriculoEstagio) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                        <i class="far fa-eye"></i> Visualizar
                                    </a>
                                    @else
                                        -
                                    @endif  
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-6">
                            <div class="card bg-default">
                                <h5 class="card-header">Trabalho(s)</h5>
                
                                <div class="card-body">
                                    @if ($estagio->trabalhoEstagio != '')
                                    <a href="{{ asset('storage/'.$estagio->trabalhoEstagio) }}" role="button" aria-pressed="true" class="btn btn-primary btn-sm" target="_new" title="Visualizar">
                                        <i class="far fa-eye"></i> Visualizar
                                    </a>
                                    @else
                                        -
                                    @endif  
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@endsection