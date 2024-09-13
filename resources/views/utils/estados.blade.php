<label for="estadoPessoal" class="font-weight-bold">{{ __('Estado') }}<span class="text-danger">*</span></label>

@if($estados != null)  
    <select class="form-control" id="estadoPessoal" name="estadoPessoal" required>
        <option value="">Selecione o estado</option>
        @foreach($estados as $estado)
            <option value="{{ $estado['sglest'] }}">{{ $estado['nomest'] }}</option>
        @endforeach
    </select> 

    <script>
        $(document).ready(function()
        {
            $("#estadoPessoal").change(function() 
            {  
                $.ajax({          
                    url: "cidades/"+$("#paisPessoal").val()+"/"+ $("#estadoPessoal").val(),
                    type: "get",          
                    success: function(response)
                    {
                        $("#exibirCidades").html(response);
                    },
                });
            });
        });
    </script>    
@else
    <input type="text" class="form-control" id="estadoPessoal" name="estadoPessoal" value="{{ old('estadoPessoal') }}" required>
@endif

