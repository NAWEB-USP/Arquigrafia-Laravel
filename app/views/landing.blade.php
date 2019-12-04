@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
  <link rel="stylesheet" href="/css/landing.css">
@stop

@section('content')

  <div id="landing-carousel">
    <div class="carousel-cell" style="background-image: url('/img/landing/slide-bk-1.jpg');">
      <div class="container">
        <div class="six eight-xs columns offset-by-three offset-by-two-xs">
          <p>&nbsp;</p>
          <h2>É simples: crie uma conta, escolha suas imagens de arquitetura mais interessantes e compartilhe no Arquigrafia!</h2>
          <p class="text-center"><a href="/users/account" class="btn">Criar uma conta</a> &nbsp; <a href="/users/login" class="btn">Login</a></p>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
    <div class="carousel-cell" style="background-image: url('/img/landing/slide-bk-2.jpg');">
      <div class="container">
        <div class="six eight-xs columns offset-by-three offset-by-two-xs">
          <p>&nbsp;</p>
          <h2>Seja parte de uma comunidade de arquitetos, estudantes de arquitetura, fotógrafos e pessoas que se interessam por arquitetura.</h2>
          <p class="text-center"><a href="/users/account" class="btn">Criar uma conta</a> &nbsp; <a href="/users/login" class="btn">Login</a></p>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
    <!-- 11960 -->
    <div class="carousel-cell" style="background-image: url('/img/landing/slide-bk-4.jpg');">
      <div class="container">
        <div class="six eight-xs columns offset-by-three offset-by-two-xs">
          <p>&nbsp;</p>
          <h2>Gostou de alguma foto do Arquigrafia? Comente e baixe as imagens que você quiser! Faça upload de uma foto para compartilhar!</h2>
          <p class="text-center"><a href="/users/account" class="btn">Criar uma conta</a> &nbsp; <a href="/users/login" class="btn">Login</a></p>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script type="text/javascript">
    var flkty = new Flickity('#landing-carousel', {
      cellAlign: 'left',
      contain: true
    });
  </script>
@stop
