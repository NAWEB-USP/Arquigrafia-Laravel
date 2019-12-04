// JavaScript Document

/* ONREADY */
$(document).ready(function(){

  /* MENU MOBILE */
  $(".menu-hamburger").click(function(){
    if($(".menu-hamburger").hasClass("open")) {
      $(".menu-hamburger").removeClass("open");
      $(".menu").removeClass("active");
      $(".menu-collapse").removeClass("show");
    } else {
      $(".menu-hamburger").addClass("open");
      $(".menu").addClass("active");
      $(".menu-collapse").addClass("show");
    }
  });

    $('.window .close').click(function (e) {
      e.preventDefault();
      $('#mask').fadeOut();
      $('.window').fadeOut('fast');
    });

    $('#mask').click(function () {
      $(this).fadeOut();
      $('.window').fadeOut('fast');
    });

    $('#printer_icon').click(function() {
      window.print();
      return false;
    });

    $('#delete_button').click(function(e){
      e.preventDefault();
      $('#registration_delete form').attr('action', this.href);
      if ($(this).hasClass('album'))
        $('#registration_delete p').html('Tem certeza que deseja excluir este álbum?');
      else if($(this).hasClass('institution'))
        $('#registration_delete p').html('A imagem ou vídeo institucional pode estar comentada, tem certeza que deseja excluir a imagem ou video?');
      else
        $('#registration_delete p').html('A imagem ou vídeo pode estar avaliada,tem certeza que deseja excluir a imagem ou video?');

      $('#mask').fadeIn('fast');
      $('#confirmation_window').fadeIn('slow');

    });

    $('#delete_photo').click(function(e){
      alert(this.href);
      e.preventDefault();
      $('#registration_delete form').attr('action', this.href);
      $('#registration_delete p').html('A imagem ou vídeo pode estar avaliada por outros usuários,tem certeza que deseja excluir esta imagem?');
      $('#mask').fadeIn('fast');
      $('#confirmation_window').fadeIn('slow');

    });

    $('.title_delete').click(function(e){
      e.preventDefault();
      $('#registration_delete form').attr('action', this.href);
      if ($(this).hasClass('album'))
        $('#registration_delete p').html('Tem certeza que deseja excluir este álbum?');
      else
        $('#registration_delete p').html('<center>Ao clicar em confirmar a imagem ou vídeo  será excluída do seu perfil e álbum. </center> <br><center><strong> Tem certeza que deseja excluir esta imagem ou vídeo  ?</strong></center>');
      $('#mask').fadeIn('fast');
      $('#confirmation_window').fadeIn('slow');
    });

    $('.title_plus').on('click', function (e){
      e.preventDefault();
      $('#mask').fadeIn('fast');
      $('#form_window').fadeIn('slow');
      $.get(this.href).done(function(data) {
        $("#registration").empty();
        $("#registration").append(data);
      })
      .fail(function() {
        console.log("Erro ao tentar carregar ábluns via AJAX!");
      });
    });

    $('#stoaLogin').on('click', function (e) {
      e.preventDefault();
      $('#mask').fadeIn('fast');
      $('#form_login_window').fadeIn('slow');
    });

    $('#institutionLogin').on('click', function (e) {
      e.preventDefault();
      $('#mask').fadeIn('fast');
      $('#form_login_inst_window').fadeIn('slow');
    });

    $('.tabs .tab-links a').on('click', function(e) {
      e.preventDefault();
      var currentAttrValue = $(this).attr('href');
      $('.tabs ' + currentAttrValue).fadeIn('slow').siblings().hide();
      $(this).parent('li').addClass('active').siblings().removeClass('active');
    });

});
