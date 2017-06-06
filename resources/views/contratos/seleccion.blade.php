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

        $(".button").click(function() {
          var id=$(this).attr('id');
          $("#membresia").val(id);
          $('.form').submit();
        });
    });
</script>
@endsection





@section('contenido')

<h1 class="ui header center aligned">Selecciona tu membresia</h1>
<form method="POST" action="{{action('ContratoController@seleccionaMembresia')}}" class="ui form">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" name="membresia" id="membresia" value="">
  <table class="ui celled structured table">
    <thead>
      <tr>
        <th rowspan="2">Servicio</th>
        <th class="center aligned" colspan="3">Tipos de membresia</th>
      </tr>
      <tr>
        <th class="center aligned" >Básica</th>
        <th class="center aligned" >Óptima</th>
        <th class="center aligned">Integral</th>
      </tr>
    </thead>
    <tbody>
      <tr>
          <td colspan="4" class="center aligned">
            <b> WEB </b>
          </td>
      </tr>

      <tr>
        <td><i>Directorio</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>
      
      <tr>
        <td><i>Base de Datos</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Video Prólogo</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Promociones en sitio web (en el año)</i></td>
        <td class="center aligned">
          1 
        </td>
        <td class="center aligned">
          2 
        </td>
        <td class="center aligned">
          3 
        </td>
      </tr>

      <tr>
        <td><i>Publicaciones Redes Sociales (en el año)</i></td>
        <td class="center aligned">
          6 
        </td>
        <td class="center aligned">
          12 
        </td>
        <td class="center aligned">
          20 
        </td>
      </tr>

      <tr>
          <td colspan="4" class="center aligned">
            <b> LIBRO </b>
          </td>
      </tr>

      <tr>
        <td><i>Copias</i></td>
        <td class="center aligned">
          50
        </td>
        <td class="center aligned">
          100
        </td>
        <td class="center aligned">
          200
        </td>
      </tr>

      <tr>
        <td><i>Artículo</i></td>
        <td class="center aligned">
          Páginas Finales
        </td>
        <td class="center aligned">
          Páginas Intermedias
        </td>
        <td class="center aligned">
          Primeras Páginas
        </td>
      </tr>

      <tr>
        <td><i>No. Páginas (máximo)</i></td>
        <td class="center aligned">
          10
        </td>
        <td class="center aligned">
          15
        </td>
        <td class="center aligned">
          20
        </td>
      </tr>

      <tr>
        <td><i>Nombre en Portada</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Foto en 2a de forros</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Video Realidad Aumentada</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Publicación en otro libro</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>
          <tr>
          <td colspan="4" class="center aligned">
            <b> LANZAMIENTO </b>
          </td>
      </tr>

      <tr>
        <td><i>Presencia Material Visual</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Tiempo en Podium (minutos)</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          5
        </td>
        <td class="center aligned">
          10
        </td>
      </tr>

      <tr>
        <td><i>No. de Invitados</i></td>
        <td class="center aligned">
          5
        </td>
        <td class="center aligned">
          10
        </td>
        <td class="center aligned">
          20
        </td>
      </tr>

      <tr>
        <td><i>Invitación Digital</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Mención en Video Lanzamiento</i></td>
        <td class="center aligned">
          <i class="large red minus icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>
      </tr>
      <tr>
          <td colspan="4" class="center aligned">
            <b> COWORKING </b>
          </td>
      </tr>

      <tr>
        <td><i>Talleres</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Intercambio Clientes</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>

      <tr>
        <td><i>Eventos Seguimiento</i></td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
        <td class="center aligned">
          <i class="large green checkmark icon"></i>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
            <a id="1" class="fluid ui big button blue"> Seleccionar </a>
        </td>
        <td>
            <a id="2" class="fluid ui big button blue"> Seleccionar </a>
        </td>
        <td>
            <a id="3" class="fluid ui big button blue"> Seleccionar </a>
        </td>
      </tr>

      

    </tbody>
  </table>
</form>
@endsection