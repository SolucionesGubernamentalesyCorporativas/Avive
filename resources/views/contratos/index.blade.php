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

    <form class="ui form attached fluid segment" enctype="multipart/form-data">

        <!-- INICIA INFO PERSONAL -->

        <h4 class="ui dividing header">Información Personal</h4>
        <div class="field">
            <div class="fields">
                <div class="twelve wide field">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" placeholder="Pedro Oscar Sanchez Sanchez">
                </div>
                <div class="four wide field">
                    <label>Edad</label>
                    <input type="number" name="edad" placeholder="33">
                </div>
            </div>
        </div>

        
        <div class="field">
            <label>Correo Electrónico</label>
            <input name="email" placeholder="pedro@gmail.com" type="text">  
        </div>

        <div class="field">
            <label>Nacionalidad</label>
            <div class="two fields">
                <div class="field">
                    <select name="nacionalidad" class="ui selection dropdown nacionalidad" >
                        <option value="">Nacionalidad</option>
                        <option value="1">Mexicana</option>
                        <option value="0">Otra</option>
                    </select>
                </div>

                <div class="field" id="nac_otra">
                    <input name="nac_otra" placeholder="Escriba cual" type="text">
                </div>

                <div class="field" id="curp">
                    <input name="curp" placeholder="CURP" type="text">
                </div>
            </div>
        </div>


        <!-- TERMINA INFO PERSONAL -->

        <!-- INICIA COMPRAS Y FACTURACION -->

        <h4 class="ui dividing header">Datos de Contratación y Facturación</h4>

        
        <div class="inline field" id="curp">
            <label>RFC</label>
            <input name="rfc" placeholder="AAAA000000***" type="text">   
            <div class="ui left pointing label" id="mensaje_rfc"> </div>
        </div>


        <div class="field">
            <label>Domicilio</label>
            <input name="domicilio" placeholder="Blvd. Centro Sur 120 Queretaro Queretaro" type="text">  
        </div>

        <div class="two fields">
            <div class="field">
                <label>Membresía</label>
                <select name="membresia" class="ui selection dropdown" >
                    <option value="">Membresía</option>
                    <option value="1">Básica</option>
                    <option value="2">Óptima</option>
                    <option value="3">Integral</option>
                </select>
            </div>

            <div class="field">
                <label>Plan de Pagos</label>
                <select name="pagos" class="ui selection dropdown" >
                    <option value="">Forma de Pago </option>
                    <option value="1">1 pago (-20%)</option>
                    <option value="2">3 pagos (-10%)</option>
                    <option value="3">6 pagos</option>
                </select>
            </div>

        </div>

        


        <h4 class="ui dividing header">Anexo de Documentos</h4>

        <div class="field">
            <label>Domicilio</label>
            <input name="domicilio" placeholder="Blvd. Centro Sur 120 Queretaro Queretaro" type="text">  
        </div>

        <input type="file" name="photo">

  <div class="ui blue submit button">Submit</div>
</form>




@endsection