@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row">
        <div class="col-sm-3 text-center">
            @include('gcub.menu')
        </div>
        <div class="col-sm-9">
            <div class="card bg-default">
                <h5 class="card-header">Requerimento de Primeira Matrícula</h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="text-danger mb-4" :errors="$errors" />
                    
                    <form class="needs-validation" novalidate method="POST" action="gcub/documento/store" enctype="multipart/form-data">
                        @csrf
                        @include('gcub.partials.form_documentos')
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Enviar</button>
                    </form>
                    <br/>

                    <h4>DOCUMENTOS EXIGIDOS PARA A MATRÍCULA</h4>

                    @if($nivel == 'Mestrado')
                        <p>9.1. Requerimento de Primeira Matrícula Regular, devidamente preenchido e assinado;</p>

                        <p class="ml-4">9.1.1. Para os alunos de Mestrado deverá ser assinado pelo coordenador do PPGEM, pois nos primeiros 90 dias após a matrícula o aluno ficará sob orientação acadêmica, sendo que até o final deste prazo deverá entregar na secretaria da Comissão Coordenadora do Programa o Plano de Estudos de dissertação e o Termo de aceite de orientação;</p>

                        <p class="ml-4">9.1.2. Para os alunos de Doutorado Direto deverá ser assinado com a concordância do Orientador;</p>

                        <p>9.2. Cópia do CPF – dispensada apresentação caso conste no RG (obrigatório para Estrangeiros);</p>

                        <p>9.3. Cópia do RG (não serão aceitos CNH, Registro de Classe, Registro Militar ou outros);</p>

                        <p>9.4. Para candidatos estrangeiros: a) Cópia do RNE ou Protocolo (que contenha o tipo de Visto: Temporário IV ou Mercosul ou Permanente) com número e validade, para os candidatos estrangeiros; b) Cópia do Passaporte (folhas onde constem identificação, número do passaporte e validade), para os candidatos estrangeiros;</p>

                        <p>9.5. Cópia da Certidão de Nascimento e ou Casamento;</p>

                        <p>9.6. Cópia do Diploma ou Declaração de Conclusão do curso de Graduação, contendo a data em que foi realizada a Colação de Grau;</p>

                        <p>9.7. Cópia do Histórico Escolar de conclusão da graduação, contendo a data de colação de grau. Atenção ao item 6.2.1 deste edital;</p>

                        <p>9.8. 01 (uma) foto 3x4 recente.</p>
                    @else    
                        <p>7.1. Requerimento de Primeira Matrícula Regular, devidamente preenchido e assinado pelo coordenador do PPGEM e pelo Orientador;</p>

                        <p>7.2. Cópia do diploma frente e verso, ou declaração de conclusão do Mestrado;</p>
                        
                        <p>7.3. Cópia do histórico escolar do Mestrado;</p>
                        
                        <p>7.4. Cópia do diploma frente e verso do curso de Graduação;</p>

                        <p>7.5. Cópia do histórico escolar de conclusão da graduação;</p>

                        <p>7.6. Cópia do CPF (inclusive para Estrangeiros);</p>

                        <p>7.7. Cópia do RG (não serão aceitos CNH, Registro de Classe, Registro Militar ou outros);</p>

                        <p>7.8. Cópia do RNE ou Protocolo com número para candidatos(as) estrangeiros(as);</p>

                        <p>7.9. Cópia da Certidão de Nascimento e ou Casamento;</p>

                        <p>7.10. 01 (uma) foto 3x4 recente.</p>
                    @endif
                </div>
            </div>            
        </div>
    </div>
</main>

@endsection