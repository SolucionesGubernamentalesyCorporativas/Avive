@extends('layout.app')

@section('title')
AVIVE - CONTRATOS
@endsection


@section('script')
    $(document).ready(function(){
        $('.dropdown').dropdown();
    });
@endsection





@section('contenido')

<div class="ui info attached message">
  <div class="header">
    Por favor llena la información correspondiente a tu contratación.
  </div>
  <p>Todos los elementos que anexes, tendrán que tener formato jpg,jpeg, o png.</p>
</div>

    <form class="ui form attached fluid segment">
        <div class="field">
            <div class="fields">
                <div class="twelve wide field">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre_afiliado" placeholder="Pedro Oscar Sanchez Sanchez">
                </div>
                <div class="four wide field">
                    <label>Edad</label>
                    <input type="number" name="edad_afiliado" placeholder="33">
                </div>
            </div>
        </div>
        <div class="ui selection dropdown">
            <input type="hidden" name="gender">
            <i class="dropdown icon"></i>
            <div class="default text">Membresía</div>
            <div class="menu">
                <div class="item" data-value="1">Básica</div>
                <div class="item" data-value="2">Óptima</div>
                <div class="item" data-value="3">Integral</div>
            </div>
        </div>

        <div class="field">
            <label>Username</label>
            <input placeholder="Username" type="text">
        </div>

  <div class="field">
    <label>Password</label>
    <input type="password">
  </div>
  <div class="inline field">
    <div class="ui checkbox">
      <input type="checkbox" id="terms">
      <label for="terms">I agree to the terms and conditions</label>
    </div>
  </div>
  <div class="ui blue submit button">Submit</div>
</form>




@endsection