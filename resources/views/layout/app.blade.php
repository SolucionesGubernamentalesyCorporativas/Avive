<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>@yield('title')</title>
  <link rel="stylesheet" type="text/css" href="/css/semantic/semantic.min.css">

  @yield('script')

</head>


  <body>

  <div class="ui borderless main menu" style="">
    <div class="ui text  container">
      <div href="#" class="header item">
        <img class="logo" src="assets/images/logo.png">
        Avive Contratación de servicios
      </div>
    </div>
  </div>

  <div class="ui text container">
    @yield('contenido')
  </div>
  </body>

</html>