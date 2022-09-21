@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <br/>        
            <div class="card bg-default">
                <h5 class="card-header">Inscrições Abertas</h5>
                
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

                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Aluno Especial - Pós-Graduação em Engenharia de Materiais - PPGEM</td>
                                <td>de 01/07/2022 a 08/07/2022</td>
                            </tr>
                            <tr>
                                <td>Aluno Especial - Pós-Graduação em Projetos Educacionais de Ciências - PPGPE</td>
                                <td>de 01/07/2022 a 08/07/2022</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection