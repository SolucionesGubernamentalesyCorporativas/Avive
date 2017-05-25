@extends('layout.app')




@section('script')
<script>
    $(document).ready(function(){
        $("#nac_otra").hide();
        $("#curp").hide();
        $("#mensaje_rfc").hide();

        $('.dropdown').dropdown();
        $(".nacionalidad").dropdown({
            onChange: function(val){
                //1 es para mexicanos
                if(val!=1){
                    $("#nac_otra").fadeIn();
                    $("#curp").hide();
                    $("input[name='rfc']").val('XEXX010101000');
                    $("#mensaje_rfc").html("RFC por defecto para clientes extranjeros");
                    $("#mensaje_rfc").fadeIn().delay(1500).fadeOut();

                }else{
                    $("#nac_otra").hide();
                    $("#curp").fadeIn();
                    $("input[name='rfc']").val('XAXX010101000');
                    $("#mensaje_rfc").html("RFC por defecto para clientes no registrados");
                    $("#mensaje_rfc").fadeIn().delay(1500).fadeOut();
                }

            }
        });
        
    });
</script>
@endsection





@section('contenido')

<div class="ui info attached message">
  <div class="header">
    Por favor llena la información correspondiente a tu contratación.
  </div>
  <p>Todos los elementos que anexes, tendrán que tener formato jpg,jpeg, o png.</p>
</div>
@if($errors->any())
<div class="ui error attached message">
    <i class="close icon"></i>
    <div class="header">
        Hubo problemas con el registro:
    </div>
    <ul class="list">
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>
@endif

    <form method="POST" action="{{action('ContratoController@store')}}" class="ui form attached fluid segment" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- INICIA INFO PERSONAL -->

        <h4 class="ui dividing header">Información Personal</h4>
        <div class="field">
            <div class="fields">
                @if($errors->has('nombre'))
                <div class="twelve wide field required error">
                @else
                <div class="twelve wide field required">
                @endif
                    <label>Nombre Completo</label>
                    <input value="{{old('nombre')}}" type="text" name="nombre" placeholder="Pedro Oscar Sanchez Sanchez">
                </div>
                @if($errors->has('edad'))
                <div class="four wide field required error">
                @else
                <div class="four wide field required">
                @endif
                    <label>Edad</label>
                    <input value="{{old('edad')}}" type="text" name="edad" placeholder="TREINTA">
                </div>
            </div>
        </div>

        @if($errors->has('email'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>Correo Electrónico</label>
            <input value="{{old('email')}}" name="email" placeholder="pedro@gmail.com" type="text">  
        </div>

        @if($errors->has('nacionalidad'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>Nacionalidad</label>
            <div class="two fields">
                <div class="field">
                    <select  name="nacionalidad" class="ui selection dropdown nacionalidad" >
                        <option value="">Nacionalidad</option>
                        <option value="1">Mexicana</option>
                        <option value="0">Otra</option>
                    </select>
                </div>

                <div class="field required" id="nac_otra">
                    <input name="nac_otra" placeholder="Escriba cual" type="text">
                </div>

                <div class="field required" id="curp">
                    <input name="curp" placeholder="CURP" type="text">
                </div>
            </div>
        </div>


        <!-- TERMINA INFO PERSONAL -->

        <!-- INICIA COMPRAS Y FACTURACION -->

        <h4 class="ui dividing header">Datos de Contratación y Facturación</h4>

        
        <div class="inline field" id="curp">
            <label>RFC</label>
            <input value="{{old('rfc')}}" name="rfc" placeholder="AAAA000000***" type="text">   
            <div class="ui left pointing label" id="mensaje_rfc"> </div>
        </div>


        @if($errors->has('domicilio'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>Domicilio</label>
            <input value="{{old('domicilio')}}"  name="domicilio" placeholder="Blvd. Centro Sur 120 Queretaro Queretaro" type="text">  
        </div>

        <div class="two fields ">
            @if($errors->has('membresia'))
            <div class="field required error">
            @else
            <div class="field required">
            @endif
                <label>Membresía</label>
                <select name="membresia" class="ui selection dropdown" >
                    <option value="">Membresía</option>
                    @foreach($membresias as $membresia)
                        <option value="{{$membresia->id}}">{{$membresia->nombre}}</option>
                    @endforeach
                </select>
            </div>

            @if($errors->has('pagos'))
            <div class="field required error">
            @else
            <div class="field required">
            @endif
                <label>Plan de Pagos</label>
                <select name="pagos" class="ui selection dropdown" >
                    <option value="">Forma de Pago </option>
                    @foreach($pagos as $pago)
                        <option value="{{$pago->id}}">{{$pago->nombre}}</option>
                    @endforeach
                </select>
            </div>

        </div>

        


        <h4 class="ui dividing header">Anexo de Documentos</h4>

        @if($errors->has('curp_file'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>CURP o Pasaporte</label>
            <input value="{{old('curp_file')}}" type="file" name="curp_file"> 
        </div>

        @if($errors->has('comprobante'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>Comprobante de Domicilio</label>
            <input value="{{old('comprobante')}}" type="file" name="comprobante"> 
        </div>

        @if($errors->has('rfc_file'))
        <div class="field required error">
        @else
        <div class="field required">
        @endif
            <label>Cédula de Identificación Fiscal</label>
            <input value="{{old('rfc_file')}}" type="file" name="rfc_file"> 
        </div>

        

        <input type="submit" class="ui blue submit button"/>
</form>




@endsection