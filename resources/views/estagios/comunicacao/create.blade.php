@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action">Home</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card bg-default">
                <h5 class="card-header">Processo Seletivo Estágiário Comunicação 2025</h5>
                
                <div class="card-body">
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

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="estagios/comunicacao/store" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col-2">
                                    <label for="cpfEstagio" class="font-weight-bold">{{ __('CPF') }}<span class="text-danger">*</span></label>            
                                    <input type="text" class="form-control" id="cpfEstagio" name="cpfEstagio" value="{{ old('cpfEstagio', session('cpfEstagio')) }}" required disabled>
                                </div>

                                <div class="col">               
                                    <label for="nomeEstagio" class="font-weight-bold">Nome Completo<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomeEstagio" name="nomeEstagio" value="{{  old('nomeEstagio') }}" required />
                                </div>
                                <div class="col">
                                    <label for="emailEstagio" class="font-weight-bold">Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="emailEstagio" name="emailEstagio" value="{{ old('emailEstagio') }}" required />
                                </div>   
                                <div class="col-2">                            
                                    <label for="telefoneEstagio" class="font-weight-bold">{{ __('Telefone') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telefoneEstagio" name="telefoneEstagio" value="{{ old('telefoneEstagio')}}" required>
                                </div>              
                            </div>
                        </div>

                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col-2">  
                                    <label for="cep" class="font-weight-bold">CEP<span class="text-danger">*</span></label>
                                    <input type="text" id="cep" name="cep" value="{{ old('cep') }}" class="form-control" maxlength="9" required>
                                </div>
                                <div class="col-8">  
                                    <label for="logradouro" class="font-weight-bold">Logradouro<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') }}"  maxlength="255" required> 
                                </div>
                                <div class="col-2">  
                                    <label for="numero" class="font-weight-bold">Número<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="numero" name="numero" value="{{ old('numero') }}"  maxlength="5" required>
                                </div>
                            </div>   
                        </div>

                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col">
                                    <label for="complemento" class="font-weight-bold">Complemento</label>
                                    <input class="form-control" type="text" id="complemento" name="complemento" value="{{ old('complemento') }}" maxlength="255">
                                </div>
                                <div class="col">
                                    <label for="bairro" class="font-weight-bold">Bairro<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="bairro" name="bairro" value="{{ old('bairro') }}" maxlength="255" required>
                                </div>
                                <div class="col">
                                    <label for="cidade" class="font-weight-bold">Cidade<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="localidade" name="localidade" value="{{ old('localidade') }}" maxlength="255" required>
                                </div>
                                <div class="col">
                                    <label for="uf" class="font-weight-bold">UF<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2" required>
                                </div>                        
                            </div>              
                        </div>
                        
                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col">  
                                    <label for="cursoEstagio" class="font-weight-bold">Curso<span class="text-danger">*</span></label>
                                    <input type="text" id="cursoEstagio" name="cursoEstagio" value="{{ old('cursoEstagio') }}" class="form-control" required>
                                </div>
                                <div class="col-2">  
                                    <label for="semestreEstagio" class="font-weight-bold">Semestre<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="semestreEstagio" name="semestreEstagio" value="{{ old('semestreEstagio') }}" maxlength="5" required>
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="card bg-default">
                                    <h6 class="card-header">Considerando a escala de 0 a 5 (em que 0 significa "nenhum conhecimento" e 5 significa "domino totalmente"), indique o seu nível</h6>
                    
                                    <div class="card-body">
                                        <div class="form-group">  
                                            <div class="form-row">
                                                <div class="col">  
                                                    <label for="facebookEstagio" class="font-weight-bold">Facebook<span class="text-danger">*</span></label>
                                                    <input type="range" onInput="$('#rangevalFacebook').html($(this).val())" class="form-control-range" id="facebookEstagio" name="facebookEstagio" value="{{ old('facebookEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalFacebook">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="instagramEstagio" class="font-weight-bold">Instagram<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalInstagram').html($(this).val())" type="range" id="instagramEstagio" name="instagramEstagio" value="{{ old('instagramEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalInstagram">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="twitterEstagio" class="font-weight-bold">Twitter<span class="text-danger">*</span></label>
                                                    <input type="range" onInput="$('#rangevalTwitter').html($(this).val())" class="form-control-range" id="twitterEstagio" name="twitterEstagio" value="{{ old('twitterEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalTwitter">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="wordEstagio" class="font-weight-bold">Word<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalWord').html($(this).val())" type="range" id="wordEstagio" name="wordEstagio" value="{{ old('wordEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalWord">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">  
                                            <div class="form-row">
                                                <div class="col">  
                                                    <label for="excelEstagio" class="font-weight-bold">Excel<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalExcel').html($(this).val())" type="range" id="excelEstagio" name="excelEstagio" value="{{ old('excelEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalExcel">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="powerPointEstagio" class="font-weight-bold">PowerPoint<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalPowerPoint').html($(this).val())" type="range" id="powerPointEstagio" name="powerPointEstagio" value="{{ old('powerPointEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalPowerPoint">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="podcastEstagio" class="font-weight-bold">Podcast<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalPodcast').html($(this).val())" type="range" id="podcastEstagio" name="podcastEstagio" value="{{ old('podcastEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalPodcast">0</span>
                                                </div>
                                                <div class="col">  
                                                    <label for="doodleEstagio" class="font-weight-bold">Doodle<span class="text-danger">*</span></label>
                                                    <input class="form-control-range" onInput="$('#rangevalWord').html($(this).val())" type="range" id="doodleEstagio" name="doodleEstagio" value="{{ old('doodleEstagio', 0) }}" min="0" max="5"step="1" required>
                                                    <span id="rangevalDoodle">0</span>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col">
                                <div class="card bg-default">
                                    <h6 class="card-header">Idiomas
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addIdioma">
                                            Incluir Idioma
                                        </button>
                                    </h6>
                    
                                    <div class="card-body">
                                        <div id="exibirIdioma">

                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col">
                                <div class="card bg-default">
                                    <h6 class="card-header">Redes Sociais</h6>
                    
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col">    
                                                    <label for="facebookTextEstagio" class="font-weight-bold">Facebook</label>
                                                    <input type="text" id="facebookTextEstagio" name="facebookTextEstagio" value="{{ old('facebookTextEstagio') }}" class="form-control">
                                                </div>

                                                <div class="col">    
                                                    <label for="instagramTextEstagio" class="font-weight-bold">Instagram</label>
                                                    <input class="form-control" type="text" id="instagramTextEstagio" name="instagramTextEstagio" value="{{ old('instagramTextEstagio') }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">  
                                            <div class="form-row">
                                                <div class="col">   
                                                    <label for="twitterTextEstagio" class="font-weight-bold">Twitter</label>
                                                    <input class="form-control" type="text" id="twitterTextEstagio" name="twitterTextEstagio" value="{{ old('twitterTextEstagio') }}">
                                                </div>
                                                <div class="col"> 
                                                    <label for="linkedinTextEstagio" class="font-weight-bold">Linkedin</label>
                                                    <input class="form-control" type="text" id="linkedinTextEstagio" name="linkedinTextEstagio" value="{{ old('linkedinTextEstagio') }}" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col-4">  
                                    <label for="curriculoEstagio" class="font-weight-bold">Se desejar compartilhar o seu CV, anexe-o aqui</label>
                                    <input type="file" class="form-control-file" id="curriculoEstagio" name="curriculoEstagio" required>
                                </div>
                                <div class="col-8">  
                                    <label for="trabalhoEstagio" class="font-weight-bold">Se desejar compartilhar algum(ns) trabalho(s) realizado(s) por você, anexe-o(s) aqui. (É necessário comprovar a autoria)</label>
                                    <input type="file" class="form-control-file" id="trabalhoEstagio" name="trabalhoEstagio" required>                                
                                </div>
                            </div>   
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@include('estagios.comunicacao.modal')
@endsection