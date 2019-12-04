<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ARQUIGRAFIA</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="/css/oam.css">
  <link rel="stylesheet" href="/js/vmsg/vmsg.css">
</head>
<body>
  <header class="shadow">
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <div class="logo"><img src="/img/logo.chou.museu.png" alt=""></div>
        </div>
        <div class="col-4 offset-4">
          <form action="" class="search-form text-right"><i class="material-icons">search</i></form>
        </div>
      </div>
    </div>
  </header>

  @yield('content')

  <p>&nbsp;</p>

  <footer>
    <div class="container-fluid">
      <div class="row pt-1">
      <div class="col-3 text-center">
        <a href="/">
          <i class="icons ico-acervo"></i>
          <p>Acervo</p>
        </a>
      </div>
      <div class="col-3 text-center">
        <a href="/oam/">
          <i class="icons ico-lugares"></i>
          <p>Lugares</p>
        </a>
      </div>
      <div class="col-3 text-center">
        <a href="{{ URL::to('/users/' . Auth::id()) }}">
          <i class="icons ico-perfil"></i>
          <p>Perfil</p>
        </a>
      </div>
      <div class="col-3 text-center">
        <i class="icons ico-menu"></i>
        <p>Menu</p>
      </div>
      </div>
    </div>
  </footer>


  <script src="/js/jquery.min.js"></script>
  <script src="/js/masonry/masonry.pkgd.min.js"></script>
  <script src="/js/oam.js"></script>

  @yield('scripts')

</body>
</html>
