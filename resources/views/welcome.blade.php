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
                <h5 class="card-header">Inscrições Abertas</h5>
                
                <div class="card-body">
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