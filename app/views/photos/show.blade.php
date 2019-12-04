@extends('layouts.default')

@section('head')

  <title>Arquigrafia - {{ $photos->name }}</title>

  <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/checkbox.css" />
  <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4fdf62121c50304d"></script>

  <!-- jBox -->
  <!-- <script src="//code.jboxcdn.com/0.4.7/jBox.min.js"></script> -->
  <link href="//code.jboxcdn.com/0.4.7/jBox.css" rel="stylesheet">

  <!-- Handlebars -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>

  <!-- Suggestions Modal -->
  <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/suggestions/suggestions-modal.css" />
  <script type="text/javascript" src="{{ URL::to("/") }}/js/dist/suggestions.bundle.js"></script>

  <!-- Google Maps API -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuBk5ghbTdpdm_nBWg6xHEzdRXdryK6rU&callback=initMap"></script>
  <script type="text/javascript">
  // Missing fields and questions (to show on Modal)
  var photo = {{ json_encode($photos) }};
  var user = {{ json_encode($user) }}
  var missingFields = {{ json_encode($missing) }};
  var isReviewing = {{ json_encode($isReviewing) }};
  var completeness = {{ json_encode($completeness) }};

  // Getting if it's gamed
  var gamed = {{ json_encode($gamified) }};

  $(document).ready(function(){
    //MAP AND GEOREFERENCING CREATION AND SETTING
    var geocoder;
    var map;

    function initialize() {
      var street = "{{ $photos->street }}";
      var district = "{{ $photos->district }}";
      var city = "{{ $photos->city }}";
      var state = "{{ $photos->state }}";
      var country = "{{ $photos->country }}";
      var address;
      if (street) address = street + "," + district + "," + city + "-" + state + "," + country;
      else if (district) address = district + "," + city + "-" + state + "," + country;
      else address = city + "-" + state + "," + country;

      geocoder = new google.maps.Geocoder();

      var latlng = new google.maps.LatLng(-34.397, 150.644);
      var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }

      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          // map.fitBounds(results[0].geometry.bounds);
          var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });
        } else {
          console.log("Geocode was not successful for the following reason: " + status);
        }
      });
    }

    initialize();
  });
  </script>
  <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/jquery.fancybox.css" />
  <script type="text/javascript" src="{{ URL::to("/") }}/js/jquery.fancybox.pack.js"></script>
  <script type="text/javascript" src="{{ URL::to("/") }}/js/photo.js"></script>
@stop

@section('content')

  @if (Session::get('message'))
    <div class="container">
      <div class="twelve columns">
          <div class="message">{{ Session::get('message') }}</div>
      </div>
    </div>
  @endif

  <!--   MEIO DO SITE - ÁREA DE NAVEGAÇ?Ã?O   -->
  <div id="content" class="container">
    <!--   COLUNA ESQUERDA   -->
    <div class="eight columns">
      <!--   PAINEL DE VISUALIZACAO - SINGLE   -->
      <div id="single_view_block">
        <!--   NOME / STATUS DA FOTO   -->
        <div>
          <div class="six columns alpha">
            <h1>
              <a href="{{ URL::to("/search?q=".$photos->name)}}"> {{ $photos->name }} </a>
            </h1>
          </div>
          <div id="img_top_itens" class="six columns omega">
            <span class="right" title="{{ $commentsMessage }}">
              <i id="comments"></i><small>{{ $commentsCount }}</small>
            </span>
            <span class="right" title="{{ $photos->likes->count() }} pessoas curtiram essa imagem">
              <i id="likes"></i> <small>{{ $photos->likes->count() }}</small>
            </span>
            @if ($institutionId == NULL && $owner->equal(Auth::user()))
              <span class="right">
                <a id="delete_button" href="{{ URL::to('/photos/' . $photos->id) }}" title="Excluir imagem"></a>
              </span>
            @elseif(Session::has('institutionId') && $institutionId != NULL && $owner->equal(Auth::user()))
              <span class="right">
                <a id="delete_button" class="institution" href="{{ URL::to('/institutions/' . $photos->id) }}" title="Excluir imagem"></a>
              </span>
            @endif
            @if ( !empty($photos->dataUpload) )
              <span class="right">
                <small>Inserido em:</small>
                <a class="data_upload" href="{{ URL::to("/search?q=".$photos->dataUpload."&t=up") }}">
                  {{ $photos->dataUpload }}
                </a>
              </span>
            @endif
          </div>
        </div>

        <!--   FIM - NOME / STATUS DA FOTO   -->

        <!--   FOTO   -->
        @if ($photos->type == 'video')
          <iframe width="560" height="315" src="{{$photos->video}}" frameborder="0" allowfullscreen></iframe>
        @else
          <a class="fancybox" href="{{ URL::to("/arquigrafia-images")."/".$photos->id."_view.jpg" }}"
          title="{{ $photos->name }}" >
            <img <?php if (/*!$photos->authorized*/false) echo "oncontextmenu='return false'"?> class="single_view_image" style=""
              src="{{ URL::to("/arquigrafia-images")."/".$photos->id."_view.jpg" }}" />
          </a>
        @endif
      </div>

      <!--   BOX DE BOTOES DA IMAGEM   -->
      <div id="single_view_buttons_box" class="mb-3">
        @if ($typeSearch == '')
          <div class="two columns">
            <a href="{{ URL::previous()}}" class='btn left'>VOLTAR</a>
          </div>
        @elseif($typeSearch == 'advance')

           <div class="two columns">
            <a href="{{ URL::previous()}}&pg=1" class='btn left'>VOLTAR</a>
            </div>
        @elseif($typeSearch == 'simples')
        <div class="first columns">
        {{ Form::open(array('url' => $urlBack ,'id'=> 'frmDetailPhoto' ,'method' => 'post')) }}

          {{ Form::hidden('q', $querySearch) }}
          {{ Form::hidden('pg', "1") }}
          {{ Form::hidden('typeSearch', $typeSearch) }}
          {{ Form::hidden('visitedPage', "$currentPage") }}
          {{ Form::hidden('urlPrev', $urlBack, array('id'  => 'urlPrev') ) }}

          {{Form::submit('VOLTAR', ['class' => 'btn return-show', 'id' =>'btnBack', 'onclick' => 'return updateForm();' ])}}



        {{ Form::close() }}
        </div>
        @endif

        @if (Auth::check())
          <ul id="single_view_image_buttons">
            <li>
              <a href="{{ URL::to('/albums/get/list/' . $photos->id) }}" title="Adicione aos seus álbuns" id="plus"></a>
            </li>
            @if($type != "video")
              @if($photos->authorized)
              <li>
                <a href="{{ asset('photos/download/'.$photos->id) }}" title="Faça o download" id="download" target="_blank"></a>
              </li>
             @else
              <li>
              <a onclick="notAuthorized();return false;" href="#" title="Faça o download" id="download" target="_blank"></a>
              </li>
             @endif
            @endif
            @if(!Session::has('institutionId'))
            <li>
              <a href="{{ URL::to('/evaluations/' . $photos->id . '/evaluate?f=sb' )}}" title="Registre suas impressões sobre {{$architectureName}}" id="evaluate" ></a>
            </li>
            @endif
            <!-- LIKE-->

            @if( ! $photos->hasUserLike(Auth::user()) )
              <li>
                <a href="{{ URL::to('/like/' . $photos->id ) }}" id="like_button" title="Curtir"></a>
              </li>
            @else
              <li>
                <a href="{{ URL::to('/dislike/' . $photos->id ) }}" id="like_button" class="dislike" title="Descurtir"></a>
              </li>
            @endif
            <!-- REPORT -->
              <li>
                <a href="{{ URL::to('/reports/showModalReport/' . $photos->id) }}" title="Denunciar imagem" id="denounce_photo"></a>
              </li>

          </ul>
        @else
          <div class="six columns alpha">
            Faça o <a href="{{ URL::to('/users/login') }}">login</a> para fazer o download e comentar as imagens.
          </div>
        @endif

        <ul id="single_view_social_network_buttons">
          <li><a href="#" class="google addthis_button_google_plusone_share"><span class="google"></span></a></li>
          <li><a href="#" class="facebook addthis_button_facebook"><span class="facebook"></span></a></li>
          <li><a href="#" class="twitter addthis_button_twitter"><span class="twitter"></span></a></li>
        </ul>
      </div>
      <script type="text/javascript">
      function notAuthorized() {
        alert("O Arquigrafia empreendeu esforços para entrar em contato com os autores e ou responsáveis por esta imagem. \nSe você é o autor ou responsável, por favor, entre em contato com a equipe do Arquigrafia no e-mail: arquigrafiabr@gmail.com.");
      }
      </script>
      <!--   FIM - BOX DE BOTOES DA IMAGEM   -->

      <div class="tags">
        <h3>Tags:</h3>
        <p>
          @if (isset($tags))
            @foreach($tags as $k => $tag)
              @if ($tag->id == $tags->last()->id)
                <form id="{{$k}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                  <input type="hidden" name="q" value="{{$tag->name}}"/>
                    <a style="" href="javascript: submitform({{$k}});">
                      {{ $tag->name }}
                    </a>
                </form>
              @else
                <form id="{{$k}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                  <input type="hidden" name="q" value="{{$tag->name}}"/>
                    <a href="javascript: submitform({{$k}});">
                      {{ $tag->name }}
                    </a>,
                </form>
              @endif
            @endforeach
          @endif
          <script type="text/javascript">
            function submitform(object)
            {
              document.getElementById(object).submit();
            }
          </script>
        </p>
      </div>

      <!--   BOX DE COMENTARIOS   -->
      <div id="comments_block" class="twelve columns row alpha omega">
        <h3>Comentários</h3>
        @if(Auth::check())
          <br>
        @endif
        <?php $comments = $photos->comments; ?>

        @if (!isset($comments))
          <p>Ninguém comentou sobre {{$architectureName}}. Seja o primeiro!</p>
        @endif

        @if (Auth::check())
          {{ Form::open(array('url' => "comments/{$photos->id}")) }}
            <div class="one columns alpha omega row">
              @if (Auth::user()->photo != "")
                <img class="user_thumbnail" src="{{ asset(Auth::user()->photo); }}" />
              @else
                <img class="user_thumbnail" src="{{ URL::to("/") }}/img/avatar-48.png" width="48" height="48" />
              @endif
            </div>

            <div class="eleven columns row">
                <strong><a href="#" id="name">{{ Auth::user()->name }}</a></strong><br>
                Deixe seu comentário <br>
                {{ $errors->first('text') }}
                {{ Form::textarea('text', '', ['id'=>'comment_field']) }}
                {{ Form::hidden('user', $photos->id ) }}
                <p>{{ Form::submit('COMENTAR', ['id'=>'comment_button','class'=>'cursor btn']) }}</p>
                <br class="clear">
                </br>
                <p align="justify" style="font-size: 7pt; width: 558px">
                    Cada usuário é responsável por seus próprios comentários.
                    O Arquigrafia não se responsabiliza pelos comentários postados,
                    mas apenas por tornar indisponível no site o conteúdo considerado
                    infringente ou danoso por determinação judicial (art.19 da Lei 12.965/14).
                </p>
            </div>
            {{ Form::close() }}
          <br class="clear">
        @else
          <p>Faça o <a href="{{ URL::to('/users/login') }}">Login</a> e comente sobre {{ $architectureName }}</p>
        @endif

        @if (isset($comments))
          @foreach($comments as $comment)
            <div class="clearfix">
              <div class="one columns alpha omega row">
                <a href={{"/users/" . $comment->user->id}}>
                @if ($comment->user->photo != "")
                  <img class="user_thumbnail" src="{{ asset($comment->user->photo); }}" />
                @else
                  <img class="user_thumbnail" src="{{ URL::to("/") }}/img/avatar-48.png" width="48" height="48" />
                @endif
                </a>
              </div>
              <div class="eleven columns omega row">
                <small id={{"$comment->id"}}>
                  <a href={{"/users/" . $comment->user->id}}>{{ $comment->user->name }}</a> - {{ $comment->created_at->format('d/m/Y h:i') }}
                  <!--<img src="{{ URL::to("/") }}/img/commentNB.png" / ><small class='likes'>{{ $comment->likes->count() }}</small>-->
                </small>
                <p>{{ $comment->text }}</p>

                @if (Auth::check())
                  @if( ! $comment->hasUserLike(Auth::user()) )
                    <p> <a href="{{ URL::to('/comments/' . $comment->id . '/like' ) }}" class='like_comment' >Curtir</a></p>
                  @else
                    <p> <a href="{{ URL::to('/comments/' . $comment->id . '/dislike' ) }}" class='like_comment' class='dislike'>Descurtir</a></p>
                  @endif
                @endif
              </div>
            </div>
          @endforeach
        @endif
      </div>
      <!-- FIM DO BOX DE COMENTARIOS -->
      <!-- msy Avaliação similar-->
      @if (count($similarPhotos) > 0)
        <div id="comments_block" class="eight columns row alpha omega">
          <hgroup class="profile_block_title">
            <h3>
              <img src="{{ asset("img/evaluate.png") }}" width="16" height="16"/>
              Imagens interpretadas com média similar
            </h3>
            <span>({{count($similarPhotos) }})
              @if(count($similarPhotos)>1)
                 Imagens
              @else
                 Imagem
              @endif
            </span>
          </hgroup>

           @foreach($similarPhotos as $k => $similarPhoto)
             @if($photos->id != $similarPhoto->id)
               @if(!Session::has('institutionId'))
              <a  class="hovertext" href='{{"/evaluations/" . $similarPhoto->id . "/showSimilarAverage" }}'
                class="gallery_photo" title="{{ $similarPhoto->name }}">
                <img src="{{ URL::to("/arquigrafia-images/" . $similarPhoto->id . "_home.jpg") }}" class="gallery_photo" />
              </a>
              @else
                <a  class="hovertext" href='{{"/photos/" . $similarPhoto->id  }}'
                class="gallery_photo" title="{{ $similarPhoto->name }}">
                <img src="{{ URL::to("/arquigrafia-images/" . $similarPhoto->id . "_home.jpg") }}" class="gallery_photo" />
                </a>
              @endif
              <!--
              <a href='{{"/photos/" . $similarPhoto->id . "/evaluate" }}' class="name">
                <div class="innerbox">{{ $similarPhoto->name }}</div>
              </a>-->
             @endif
           @endforeach

        </div>
      @endif
      <!-- -->
    </div>
    <!--   FIM - COLUNA ESQUERDA   -->
    <!--   SIDEBAR   -->
    <div id="sidebar" class="four columns">
      <!--   USUARIO   -->
      <div id="single_user" class="clearfix row">
        <!--<a href="{{ URL::to("/users/".$owner->id) }}" id="user_name">-->
          @if(!is_null($ownerInstitution))
           <a href="{{ URL::to("/institutions/".$ownerInstitution->id) }}" id="user_name">
              @if($ownerInstitution->photo != "")
                <img id="single_view_user_thumbnail" src="{{ asset($ownerInstitution->photo) }}" class="user_photo_thumbnail"/>
              @else
                <img id="single_view_user_thumbnail" src="{{ URL::to("/") }}/img/avatar-institution.png" class="user_photo_thumbnail"/>
              @endif
          @elseif ($owner->photo != "")
            <a href="{{ URL::to("/users/".$owner->id) }}" id="user_name">
            <img id="single_view_user_thumbnail" src="{{ asset($owner->photo) }}" class="user_photo_thumbnail"/>
          @else
            <a href="{{ URL::to("/users/".$owner->id) }}" id="user_name">
            <img id="single_view_user_thumbnail" src="{{ URL::to("/") }}/img/avatar-48.png"
              width="48" height="48" class="user_photo_thumbnail"/>
          @endif
        </a>
        @if(!is_null($ownerInstitution))
        <h1 id="single_view_owner_name"><a href="{{ URL::to("/institutions/".$ownerInstitution->id) }}" id="name">{{ $ownerInstitution->name }}</a></h1>
        @else
        <h1 id="single_view_owner_name"><a href="{{ URL::to("/users/".$owner->id) }}" id="name">{{ $owner->name }}</a></h1>
        @endif

        @if(!is_null($ownerInstitution) && Auth::check() && !$ownerInstitution->equal(Auth::user()) && !Session::has('institutionId'))
            @if (!empty($followInstitution) && $followInstitution == true )
              <a href="{{ URL::to("/friends/followInstitution/" . $ownerInstitution->id) }}" id="single_view_contact_add">Seguir</a><br />
            @else
              <div id="unfollow-button">
                  <a href="{{ URL::to("/friends/unfollowInstitution/" . $ownerInstitution->id) }}">
                    <p class="label success new-label"><span>Seguindo</span></p>
                  </a>
              </div>
            @endif
        @elseif ( Auth::check() && !$owner->equal(Auth::user()) && !Session::has('institutionId'))
          @if (!empty($follow) && $follow == true )
            <a href="{{ URL::to("/friends/follow/" . $owner->id) }}" id="single_view_contact_add">Seguir</a><br />
          @else
            <div id="unfollow-button">
              <a href="{{ URL::to("/friends/unfollow/" . $owner->id) }}">
                  <p class="label success new-label"><span>Seguindo</span></p>
              </a>
            </div>
          @endif
        @endif
      </div>
      <!--   FIM - USUARIO   -->

      <hgroup class="profile_block_title">
        <h3><i class="info"></i> Informações</h3>
          &nbsp; &nbsp;
          @if($belongInstitution)
          <a href= '{{"/institutions/" . $photos->id . "/form/edit" }}' title="Editar informações da imagem">
          <img src="{{ asset("img/edit.png") }}" width="16" height="16"/>
          </a>
          @endif
          @if($owner->equal(Auth::user()) && $hasInstitution == false && !Session::get('institutionId'))

          <a href= '{{"/photos/" . $photos->id . "/edit" }}' title="Editar informações da imagem">
          <img src="{{ asset("img/edit.png") }}" width="16" height="16"/>
          </a>

          @endif
      </hgroup>

      {{-- @include('photo_feedback') --}}

      <div id="description_container">
      @if ( !empty($photos->description) )
        <h4>Descrição:</h4>
        <p>{{ htmlspecialchars($photos->description, ENT_COMPAT | ENT_HTML5, 'UTF-8') }}</p>
      @endif
      </div>
      @if ( !empty($photos->collection) )
        <h4>Coleção:</h4>
        <p>{{ $photos->collection }}</p>
      @endif
      <div id="imageAuthor_container">
      @if ( !empty($photos->imageAuthor) )
        <h4>Autor(es) {{ $type == "video" ? "do video" : "da Imagem " }} :</h4>
        <p>
          <a href="{{ URL::to("/search?q=".$photos->imageAuthor)}}">
            {{ $photos->imageAuthor }}
          </a>
        </p>
      @endif
      </div>
      <div id="dataCriacao_container">
      @if ( !empty($photos->dataCriacao) && $photos->getFormatDataCriacaoAttribute($photos->dataCriacao,$photos->imageDateType) != null)
        <h4>Data da Imagem:</h4>
        <p>
          <a href="{{ URL::to("/search?q=".$photos->dataCriacao."&t=img") }}">
            <!--$photos->translated_data_criacao -->
            {{ $photos->getFormatDataCriacaoAttribute($photos->dataCriacao,$photos->imageDateType) }}
          </a>
        </p>
      @endif
      </div>

      <div id="workAuthor_container">
      @if (!empty($authorsList) )
        <h4>Autor(es) do Projeto:</h4>
        <p><?php $i=1; ?>
          @foreach ($authorsList as $authors)

          <a href="{{ URL::to("/search?q=".$authors) }}">
            {{ $photos->authorTextFormat($authors); }}
          </a>
            @if($i!=count($authorsList));
            @endif
            <?php $i++; ?>
          @endforeach
        </p>
      @endif
      </div>
      <div id="workdate_container">
      @if ( !empty($photos->workdate) && $photos->getFormatWorkdateAttribute($photos->workdate,$photos->workDateType) != null )
        <h4>Data de conclusão da obra:</h4>
        <p>
          <a href="{{ URL::to("/search?q=".$photos->workdate."&t=work") }}">
            <!--$photos->translated_work_date -->
            {{ $photos->getFormatWorkdateAttribute($photos->workdate,$photos->workDateType) }}
          </a>
        </p>
      @endif
      </div>
      <div id="address_container">
      @if ( !empty($photos->street) || !empty($photos->city) ||
        !empty($photos->state) || !empty($photos->country) )
        <h4>Endereço:</h4>
        <p>
          <!-- Printing the addresss -->
          @if (!empty($photos->street) && !empty($photos->district) && !empty($photos->city))
            <a href="{{ URL::to("/search?q=".$photos->street."&city=".$photos->city) }}">
              {{ $photos->street }}, {{ $photos->district }} - {{ $photos->city }}
            </a>
            <br />
          @elseif (!empty($photos->street) && !empty($photos->district) && empty($photos->city))
            <a href="{{ URL::to("/search?q=".$photos->street) }}">
              {{ $photos->street }}, {{ $photos->district }}
            </a>
            <br />
          @elseif (!empty($photos->street) && empty($photos->district) && !empty($photos->city))
            <a href="{{ URL::to("/search?q=".$photos->street."&city=".$photos->city) }}">
              {{ $photos->street }} - {{ $photos->city }}
            </a>
            <br />
          @elseif (empty($photos->street) && !empty($photos->district) && !empty($photos->city))
            {{ $photos->district }} - {{ $photos->city }}
            <br />
          @elseif (empty($photos->street) && empty($photos->district) && !empty($photos->city))
            {{ $photos->city }}
            <br />
          @elseif (empty($photos->street) && !empty($photos->district) && empty($photos->city))
            {{ $photos->district }}
            <br />
          @elseif (!empty($photos->street) && empty($photos->district) && empty($photos->city))
            <a href="{{ URL::to("/search?q=".$photos->street) }}">
              {{ $photos->street }}
            </a>
            <br />
          @endif

          <!-- Printing the country and state -->
          @if (!empty($photos->state) && !empty($photos->country))
            <a href="{{ URL::to("/search?q=".$photos->state) }}">{{ $photos->state }}</a> - {{ $photos->country }}
          @elseif (!empty($photos->state))
            <a href="{{ URL::to("/search?q=".$photos->state) }}">{{ $photos->state }}</a>
          @elseif(!empty($photos->country))
            <a href="{{ URL::to("/search?q=".$photos->country) }}">{{ $photos->country }}</a>
          @endif
        </p>
      @endif
      </div>

      <div id="progress-bar" class="progress-bar button hidden">
        @if ($completeness['present'] != 0)
          <div id="completed" class="fill-bar fill-{{ $completeness['present'] }}">
            <span>{{ $completeness['present'] }}%</span>
            <div class="bar-info">
              <strong>Dados completos:</strong><br />
              Esta foto tem {{ $completeness['present'] }}% dos dados preenchidos pelo autor ou aceitos após revisão da comunidade.<br />
              @if (!$isReviewing && $completeness['present'] != 100)
                <a href="#" class="OpenModal" data-origin="progress-bar">Colabore com mais informações aqui</a>
              @endif
            </div>
          </div>
        @endif
        @if ($completeness['reviewing'] != 0)
          <div id="revision" class="fill-bar fill-{{ $completeness['reviewing'] }}">
            <span>{{ $completeness['reviewing'] }}%</span>
            <div class="bar-info">
              <strong>Dados em revisão:</strong><br>
              Esta foto tem {{ $completeness['reviewing'] }}% dos dados em revisão que serão validados antes de serem disponibilizados.<br />
            </div>
          </div>
        @endif
        @if ($completeness['missing'] != 0)
          <div id="missing" class="fill-bar black fill-{{ $completeness['missing'] }}">
            <span>{{ $completeness['missing'] }}%</span>
            <div class="bar-info">
              <strong>Dados a preencher:</strong><br>
              Esta foto tem {{ $completeness['missing'] }}% dos dados ainda não preenchidos.<br />
              @if (!$isReviewing)
                <a href="#" class="OpenModal" data-origin="progress-bar">Colabore com mais informações aqui</a>
              @endif
            </div>
          </div>
        @endif
      </div>



      <!-- Suggestions Modal Button -->
    @if ($photos->institution == null && $photos->type != "video")
        @if (!$isReviewing && $completeness['present'] != 100)
          <div class="modal-wrapper">
            <div class="title2">Você conhece mais informações sobre esta arquitetura?</div>

        		<div class="title1">
              Por exemplo:
              @if (isset($missing))
                @foreach($missing as $missingField)
                  @if($missingField == end($missing))
                    {{$missingField['field_name']}}?
                  @else
                    {{$missingField['field_name']}},
                  @endif
                @endforeach
              @endif
            </div>

        		<div class="modal-button OpenModal">
              @if ($user == null)
                <a href="#" data-origin="button">Faça o login e contribua com mais informações sobre esta imagem!</a>
              @else
                <a href="#" data-origin="button">Ajude a completar dados!</a>
              @endif
        		</div>
        	</div>
        @elseif($isReviewing)
          <div class="modal-wrapper">
            <div class="title1">A revisão desta imagem está temporariamente bloqueada até que a análise de sugestões feitas por membros do Arquigrafia seja concluída.</div>
          </div>
        @elseif($completeness['present'] == 100)
          <div class="modal-wrapper">
            <div class="title2">Essas informações foram definidas por membros do Arquigrafia.</div>
            <div class="title1">
              <p style="text-align: justify;">
                Se você tem alguma informação adicional sobre esta imagem, por favor,
                envie um email para <a href="mailto:arquigrafiabrasil@gmail.com">arquigrafiabrasil@gmail.com</a>
              </p>
            </div>
          </div>
        @endif

        </br>
      @endif

      <!-- Showing message on institutions -->
      @if ($photos->institution != null && $photos->type != "video")
        <div class="modal-wrapper">
          <div class="title2">Essas informações foram definidas pelo {{$photos->institution['name']}}.</div>
          <div class="title1">
            <p style="text-align: justify;">
              Se você tem alguma informação adicional sobre esta imagem, por favor,
              envie um email para <a href="mailto:{{$photos->institution['email']}}">{{$photos->institution['email']}}</a>
            </p>
          </div>
        </div>
      @endif

      <h4>Licença:</h4>
      <a class="tooltip_license"
        href="http://creativecommons.org/licenses/{{$license[0]}}/3.0/deed.pt_BR" target="_blank" >
        <img src="{{ asset('img/ccIcons/'.$license[0].'88x31.png') }}" id="ccicons"
          alt="Creative Commons License" />
        <span>
          @if (Auth::check())
            @if( trim($photos->imageAuthor) == trim($user->name) ) )
              <strong>Você é proprietário(a) desta imagem</strong>
            @else
              <strong>O proprietário desta imagem "{{ucfirst($photos->imageAuthor)}}":</strong>
            @endif
          @else
            <strong>O proprietário desta imagem "{{ucfirst($photos->imageAuthor)}}":</strong>
          @endif
          <br/>
          "{{ $license[1] }}"
        </span>
      </a>
      </br>

       <!-- GOOGLE MAPS -->
      <h4>Localização:</h4>
      <div id="map_canvas" class="single_view_map" style="width:300px; height:250px;"></div>
      </br>

      <!-- AVALIAÇÃO -->

      @if (Auth::check() && !Session::has('institutionId'))
        <a href="{{ URL::to('/evaluations/' . $photos->id . '/evaluate?f=g' ) }}">
      @endif

      @if (empty($average))
        @if(!Session::has('institutionId'))
        <h4>Interpretações da arquitetura:</h4>
        <img src="/img/GraficoFixo.png" />
        @endif
      @else
        <h4>
          <center>Média de Interpretações d{{ $architectureName }} </center>
        </h4>
        <br>
        <div id="evaluation_average"></div>
      @endif


      @if (Auth::check() && !Session::has('institutionId'))
        </a>
      @endif

      @if (Auth::check())
        @if (isset($userEvaluations) && !$userEvaluations->isEmpty() && !Session::get('institutionId'))
          <a href='{{"/evaluations/" . $photos->id . "/evaluate?f=c" }}' title="Interpretar" id="evaluate_button"
          class="btn">
            Clique aqui para alterar suas impressões
          </a> &nbsp;
        @else
          @if (empty($average) && !Session::get('institutionId'))
            <a href='{{"/evaluations/" . $photos->id . "/evaluate?f=c" }}' title="Interpretar" id="evaluate_button"
            class="btn">
              Seja o primeiro a registrar impressões sobre {{$architectureName}}
            </a> &nbsp;
          @elseif(!Session::get('institutionId'))
            <a href='{{"/evaluations/" . $photos->id . "/evaluate?f=c" }}' title="Interpretar" id="evaluate_button"
            class="btn">
              Registre você também impressões sobre {{$architectureName}}
            </a> &nbsp;
          @endif
        @endif
      @else
        @if (empty($average) && !Session::get('institutionId'))
          <p>
            Faça o <a href="{{ URL::to('/users/login') }}">Login</a>
            e seja o primeiro a registrar impressões sobre {{ $architectureName }}
          </p>
        @else
          <p>
            Faça o <a href="{{ URL::to('/users/login') }}">Login</a>
            e registre você também impressões sobre {{ $architectureName }}
          </p>
        @endif
      @endif
    <!--   FIM - SIDEBAR   -->
    </div>
  </div>
    <!--   MODAL   -->
  <div id="mask"></div>
  <div id="form_window" class="form window">
    <a class="close" href="#" title="FECHAR">Fechar</a>
    <div id="registration"></div>
  </div>
  <!-- REport-->
    <div id="form_window_report" class="form window">
        <a class="close" href="#" title="FECHAR">Fechar</a>
        <div id="registration_report"></div>
    </div>
<!-- FIM REport-->
  <div id="confirmation_window" class="window">
    <div id="registration_delete">
      <p></p>
      {{ Form::open(array('url' => '', 'method' => 'delete')) }}
        <div id="registration_buttons">
          <input type="submit" class="btn" value="Confirmar" />
          <a class="btn close" href="#">Cancelar</a>
        </div>
      {{ Form::close() }}
    </div>
  </div>
  <script src="http://code.highcharts.com/highcharts.js"></script>
  <script type="text/javascript">
    $(function () {
      var l1 = [
          @foreach($binomials as $binomial)
            '{{ $binomial->firstOption}}',
          @endforeach
      ];
      var l2 = [
          @foreach($binomials as $binomial)
            '{{ $binomial->secondOption }}',
          @endforeach
      ];
      $('#evaluation_average').highcharts({
          credits: {
              enabled: false,
          },
          chart: {
              marginRight: 80,
              width: 311,
              height: 300
          },
          title: {
              text: ''
          },
          tooltip: {
            formatter: function() {
            return ''+ l1[this.y] + '-' + l2[this.y] + ': <br>' + this.series.name + '= ' + this.x;
            },
            crosshairs: [true,true]
          },
          xAxis: {
              lineColor: '#000',
              min: 0,
              max: 100,
          },
          yAxis: [{
              lineColor: '#000',
              lineWidth: 1,
              tickAmount: {{$binomials->count()}},
              tickPositions: [
                <?php $count = 0?>
                @foreach($binomials as $binomial)
                  {{ $count }},
                  <?php $count++; ?>
                @endforeach
              ],
              title: {
                  text: ''
              },
              labels: {
                formatter: function() {
                  return l1[this.value];
                }
              }
          }, {
              lineWidth: 1,
              tickAmount: {{$binomials->count()}},
              tickPositions: [
                <?php $count = 0?>
                @foreach($binomials as $binomial)
                  {{ $count }},
                  <?php $count++; ?>
                @endforeach
              ],
              opposite: true,
              title: {
                  text: ''
              },
              labels: {
                formatter: function() {
                  return l2[this.value];
                }
              },
          }],

          series: [{
              <?php $count = 0; ?>
              data: [
                @foreach($average as $avg)
                  [{{ $avg->avgPosition }}, {{ $count }}],
                  <?php $count++ ?>
                @endforeach
              ],
              yAxis: 1,
              name: 'Média',
              marker: {
                symbol: 'circle',
                enabled: true
              },
              color: '#999999',
          },

          @if(!Session::has('institutionId'))
           {
              <?php $count = 0; ?>
              data: [
                @if(isset($userEvaluations) && !$userEvaluations->isEmpty())
                  @foreach($userEvaluations as $userEvaluation)
                    [{{ $userEvaluation->evaluationPosition }}, {{ $count }}],
                    <?php $count++ ?>
                  @endforeach
                @endif
              ],
              yAxis: 0,
              name: 'Sua impressão',
              marker: {
                symbol: 'circle',
                enabled: true
              },
              color: '#000000',
          }
          @endif
          ]
      });
    });
  </script>

  <!-- SUGGESTION MODAL HANDLEBARS COMPONENTS -->
  <script id="suggestion-modal-title" type="text/x-handlebars-template">
    <div class="title-container">
      <div class="field-icon" style="background: url(/img/suggestions-modal/@{{icon}}.png) no-repeat center center #fff;">
      </div>
      <button class="close-button"></button>
    </div>
  </script>

  <script id="suggestion-modal-title-gamefied" type="text/x-handlebars-template">
    <div class="title-container">
      <div class="points-container">
        <p><span class="bold-text">Pontos Pendentes:</span> @{{ points }}</p>
        <div class="points-info">
          <p><span class="bold-text">Pontuação Pendente:</span></p>
          <p>Você poderá obter a pontuação pendente assim que suas sugestões forem aceitas pelo autor da imagem.</p>
        </div>
      </div>
      <div class="field-icon" style="background: url(/img/suggestions-modal/@{{icon}}.png) no-repeat center center #fff;">
      </div>
      <button class="close-button"></button>
    </div>
  </script>

  <script id="suggestion-modal-text-content" type="text/x-handlebars-template">
    <div class="jBox-content sugestion">
  		<div class="field-name sugestion">
        @{{name}}
  		</div>

  		<div class="field-question sugestion">

        @{{#if imageID }}
          <img class="field-image" src="/arquigrafia-images/@{{ imageID }}_view.jpg" />
        @{{/if}}

        <div class="question-container">
          @{{question}}
        </div>

        <div>
          <textarea id="sugestion-text" class="sugestion"></textarea>
        </div>
        <div>
          <button class="enviar-button">Enviar</button>
        </div>
  		</div>
      <div style="width: 100%;">
        <div id="error-message-sugestion" class="error-message sugestion hidden">
          <p>Antes de clicar em Enviar, informe sua resposta. Caso não saiba a resposta clique em "@{{ jumpLabel }}".</p>
        </div>
      </div>
  	</div>
  </script>

  <script id="suggestion-modal-confirm-content" type="text/x-handlebars-template">
    <div class="jBox-content">
      <div class="field-name">
        @{{name}}
      </div>

      <div class="field-question">
        @{{question}}
      </div>
    </div>
  </script>

  <script id="suggestion-modal-last-page-content" type="text/x-handlebars-template">
    <div class="jBox-content">
      <div class="field-name feedback">
  			Obrigado por contribuir com o Arquigrafia! Suas sugestões foram enviadas para verificação do autor da imagem.
  		</div>
      <div>
        <p  class="label new-label">
          Se desejar, entre em contato diretamente com o autor <a href="/users/@{{ userID }}" id="last-modal-user-profile" target="_blank"><span>aqui</span></a>
        </p>
      </div>
    </div>
  </script>

  <script id="suggestion-modal-last-page-gamed-content" type="text/x-handlebars-template">
    <div class="jBox-content feedback">
      <div class="field-name feedback">
  			@{{ question }}
  		</div>


  		<div id="next-photos-container" class="image-sugestions">
        <!-- HANDLEBARS WILL RENDER THE IMAGES HERE -->
  		</div>

      <div>
        <p  class="label new-label">
          Se desejar, entre em contato diretamente com o autor <a href="/users/@{{ userID }}" id="last-modal-user-profile" target="_blank"><span>aqui</span></a>
        </p>
      </div>
    </div>
  </script>

  <script id="suggestion-modal-last-page-gamed-photos" type="text/x-handlebars-template">
    <span class="image-sugestions-text">Outras imagens para colaborar:</span>

    @{{#each photos}}
      <div class="single-image-sugestions suggestion-last-modal-image" data-id="@{{ id }}">
        <a href="/photos/@{{ id }}" target="_blank">
          <img src="/arquigrafia-images/@{{ id }}_home.jpg" />
        </a>
      </div>
    @{{/each}}

  </script>

  <script id="suggestion-modal-confirm-footer" type="text/x-handlebars-template">
    <div class="jBox-footer">
      <div class="clearfix">
        <button class="sim-button">Sim</button>
        <button class="nao-button">Não</button>
        <button class="nao-sei-button">@{{ jumpLabel }}</button>
      </div>
    </div>
    <div class="nav-steps-container">
      <nav class="nav-steps">
        <ul>
          @{{#times numItems}}
            @{{#ifCond this ../currentIndex }}
              <li class="-selected"></li>
            @{{else}}
              <li></li>
            @{{/ifCond}}
          @{{/times}}
        </ul>
      </nav>
    </div>
  </script>

  <script id="suggestion-modal-jump-footer" type="text/x-handlebars-template">
    <div class="jBox-footer sugestion">
      <div class="clearfix">
  			<button class="pular-etapa-button">@{{ label }}</button>
  		</div>
    </div>
    <div class="nav-steps-container">
      <nav class="nav-steps">
        <ul>
          @{{#times numItems}}
            @{{#ifCond this ../currentIndex }}
              <li class="-selected"></li>
            @{{else}}
              <li></li>
            @{{/ifCond}}
          @{{/times}}
        </ul>
      </nav>
    </div>
  </script>

  <script id="suggestion-modal-close-footer" type="text/x-handlebars-template">
    <div class="jBox-footer sugestion">
      <div class="clearfix">
        <button class="fechar-button">Fechar</button>
      </div>
    </div>
    <div class="nav-steps-container">
      <nav class="nav-steps">
        <ul>
          @{{#times numItems}}
            @{{#ifCond this ../currentIndex }}
              <li class="-selected"></li>
            @{{else}}
              <li></li>
            @{{/ifCond}}
          @{{/times}}
        </ul>
      </nav>
    </div>
  </script>


@stop
