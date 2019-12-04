@extends('layouts.default')
@section('head')
<title>Arquigrafia - Fotos - Upload</title>
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.core.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.tags.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/styletags.css" />

<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.core.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.tags.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.suggestions.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.filter.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.ajax.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-list.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-autocomplete-part.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/city-autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/date-work.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/rotate.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/readURL.js" charset="utf-8"></script>

<link rel="stylesheet" href="{{ URL::to("/") }}/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
@stop

@section('content')
<script type="text/javascript">
  $( window ).on('load', function() {
    $("#preview_photo").hide();
    if (document.getElementById("new_album-name").value != "") {
      var select_album = document.getElementsByClassName('select-album');
      var new_album = document.getElementsByClassName('new-album-name');
      var selector = document.getElementById('photo_album');
      var i;
      for (i = 0; i < select_album.length; i++) {
        select_album[i].style.display = "block";
      }
      for (i = 0; i < new_album.length; i++) {
        new_album[i].style.display = "none";
      }
      for (i = 0; i < selector.options.length; i++) {
        if (selector.options[i].text == document.getElementById("new_album-name").value) {
          selector.selectedIndex = i;
        }
      }
      document.getElementById("new_album-name").value = "";
    }
    else if (document.getElementById("photo_album").value != "") {
      var select_album = document.getElementsByClassName('select-album');
      var new_album = document.getElementsByClassName('new-album-name');
      var i;
      for (i = 0; i < select_album.length; i++) {
        select_album[i].style.display = "block";
      }
      for (i = 0; i < new_album.length; i++) {
        new_album[i].style.display = "none";
      }
    }
  });
</script>

<div class="photo-upload container">
  <div>
    {{ Form::open(array('url'=>'photos', 'files'=> true)) }}
    <div class="twelve columns row step-1">
      <h1><span class="step-text">Upload</span></h1>
      <div class="eleven columns alpha" id="media_type">
        <br>
        <div class="form-row">
          <input type="radio" name="type" value="photo" id="type_photo" checked="checked">
          <label for="type_photo">Foto</label><br class="clear">
        </div>
        <div class="form-row">
          <input type="radio" name="type" value="video" id="type_video">
          <label for="type_video">Vídeo</label><br class="clear">
        </div>
      </div>
      <div id="divPhoto" class="six columns alpha">
        <img src="" id="preview_photo">
        <div id="image_rotate" style="display:none;">
          <br></br>
          <a class="btn left" onclick="Rotate(document.getElementById('preview_photo'), -Math.PI/2);">Girar 90° para esquerda</a>
          <a class="btn right" onclick="Rotate(document.getElementById('preview_photo'), Math.PI/2);">Girar 90° para direita</a>
        </div>
        <br></br>
        <p>
          {{ Form::label('photo','Imagem:') }}
          {{ Form::file('photo', array('id'=>'imageUpload', 'onchange' => 'readURL(this);')) }}
          <br></br>
          <div class="error">{{ $errors->first('photo') }}</div>

        </p>
      </div>
      <div id="divVideo" class="twelve columns alpha">
        </br>
        <div class="six columns alpha">{{ Form::label('video', 'Link do vídeo youtube ou vimeo:') }}</div>
        <div class="six columns alpha">
          <p>{{ Form::text('video', $video, array('id' => 'video','style'=>'width:280px')) }} <br>
          </p>
          <div class="error">{{ $errors->first('video') }}</div>
          <p>Ex. https://www.youtube.com/watch?v=XXXXXXXX  <br>
            ou  https://vimeo.com/XXXXXXXX</p>
        </div>
      </div>
      <br>
    </div>
    <div id="registration" class="twelve columns row step-2">
      <h1><span class="step-text">Informações</span></h1>
      </br>
      <h4>Campos obrigatórios (*)</h4>
      <p>{{ Form::hidden('pageSource', $pageSource) }} </p>

      <br>
      <div class="six columns alpha row">
        <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <div class="four columns alpha">
                <p>{{ Form::label('photo_name', 'Título*:') }}</p>
              </div>
              <div class="six columns omega">
                <p>{{ Form::text('photo_name', Input::old('photo_name')) }} <br>
                  <div class="error">{{ $errors->first('photo_name') }}</div>
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="four columns alpha"><p>{{ Form::label('photo_imageAuthor', 'Autor(es) da imagem/video*:') }}</p></div>
              <div class="six columns omega">
                <p>
                  {{ Form::text('photo_imageAuthor', $user->name) }} <br>
                  <div class="error">{{ $errors->first('photo_imageAuthor') }}</div>
                </p>
                <p>Separe os autores diferentes com ";"</p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="four columns alpha"><p>{{ Form::label('photo_country', 'País*:') }}</p></div>
              <div class="six columns omega">
                <p>
                  {{ Form::select('photo_country', [ "Afeganistão"=>"Afeganistão", "África do Sul"=>"África do Sul", "Albânia"=>"Albânia", "Alemanha"=>"Alemanha", "América Samoa"=>"América Samoa", "Andorra"=>"Andorra", "Angola"=>"Angola", "Anguilla"=>"Anguilla", "Antartida"=>"Antartida", "Antigua"=>"Antigua", "Antigua e Barbuda"=>"Antigua e Barbuda", "Arábia Saudita"=>"Arábia Saudita", "Argentina"=>"Argentina", "Aruba"=>"Aruba", "Australia"=>"Australia", "Austria"=>"Austria", "Bahamas"=>"Bahamas", "Bahrain"=>"Bahrain", "Barbados"=>"Barbados", "Bélgica"=>"Bélgica", "Belize"=>"Belize", "Bermuda"=>"Bermuda", "Bhutan"=>"Bhutan", "Bolívia"=>"Bolívia", "Botswana"=>"Botswana", "Brasil"=>"Brasil", "Brunei"=>"Brunei", "Bulgária"=>"Bulgária", "Burundi"=>"Burundi", "Cabo Verde"=>"Cabo Verde", "Camboja"=>"Camboja", "Canadá"=>"Canadá", "Chade"=>"Chade", "Chile"=>"Chile", "China"=>"China", "Cingapura"=>"Cingapura", "Colômbia"=>"Colômbia", "Djibouti"=>"Djibouti", "Dominicana"=>"Dominicana", "Emirados Árabes"=>"Emirados Árabes", "Equador"=>"Equador", "Espanha"=>"Espanha", "Estados Unidos"=>"Estados Unidos", "Fiji"=>"Fiji", "Filipinas"=>"Filipinas", "Finlândia"=>"Finlândia", "França"=>"França", "Gabão"=>"Gabão", "Gaza Strip"=>"Gaza Strip", "Ghana"=>"Ghana", "Gibraltar"=>"Gibraltar", "Granada"=>"Granada", "Grécia"=>"Grécia", "Guadalupe"=>"Guadalupe", "Guam"=>"Guam", "Guatemala"=>"Guatemala", "Guernsey"=>"Guernsey", "Guiana"=>"Guiana", "Guiana Francesa"=>"Guiana Francesa", "Haiti"=>"Haiti", "Holanda"=>"Holanda", "Honduras"=>"Honduras", "Hong Kong"=>"Hong Kong", "Hungria"=>"Hungria", "Ilha Cocos (Keeling)"=>"Ilha Cocos (Keeling)", "Ilha Cook"=>"Ilha Cook", "Ilha Marshall"=>"Ilha Marshall", "Ilha Norfolk"=>"Ilha Norfolk", "Ilhas Turcas e Caicos"=>"Ilhas Turcas e Caicos", "Ilhas Virgens"=>"Ilhas Virgens", "Índia"=>"Índia", "Indonésia"=>"Indonésia", "Inglaterra"=>"Inglaterra", "Irã"=>"Irã", "Iraque"=>"Iraque", "Irlanda"=>"Irlanda", "Irlanda do Norte"=>"Irlanda do Norte", "Islândia"=>"Islândia", "Israel"=>"Israel", "Itália"=>"Itália", "Iugoslávia"=>"Iugoslávia", "Jamaica"=>"Jamaica", "Japão"=>"Japão", "Jersey"=>"Jersey", "Kirgizstão"=>"Kirgizstão", "Kiribati"=>"Kiribati", "Kittsnev"=>"Kittsnev", "Kuwait"=>"Kuwait", "Laos"=>"Laos", "Lesotho"=>"Lesotho", "Líbano"=>"Líbano", "Líbia"=>"Líbia", "Liechtenstein"=>"Liechtenstein", "Luxemburgo"=>"Luxemburgo", "Maldivas"=>"Maldivas", "Malta"=>"Malta", "Marrocos"=>"Marrocos", "Mauritânia"=>"Mauritânia", "Mauritius"=>"Mauritius", "México"=>"México", "Moçambique"=>"Moçambique", "Mônaco"=>"Mônaco", "Mongólia"=>"Mongólia", "Namíbia"=>"Namíbia", "Nepal"=>"Nepal", "Netherlands Antilles"=>"Netherlands Antilles", "Nicarágua"=>"Nicarágua", "Nigéria"=>"Nigéria", "Noruega"=>"Noruega", "Nova Zelândia"=>"Nova Zelândia", "Omã"=>"Omã", "Panamá"=>"Panamá", "Paquistão"=>"Paquistão", "Paraguai"=>"Paraguai", "Peru"=>"Peru", "Polinésia Francesa"=>"Polinésia Francesa", "Polônia"=>"Polônia", "Portugal"=>"Portugal", "Qatar"=>"Qatar", "Quênia"=>"Quênia", "República Dominicana"=>"República Dominicana", "Romênia"=>"Romênia", "Rússia"=>"Rússia", "Santa Helena"=>"Santa Helena", "Santa Kitts e Nevis"=>"Santa Kitts e Nevis", "Santa Lúcia"=>"Santa Lúcia", "São Vicente"=>"São Vicente", "Singapura"=>"Singapura", "Síria"=>"Síria", "Spiemich"=>"Spiemich", "Sudão"=>"Sudão", "Suécia"=>"Suécia", "Suiça"=>"Suiça", "Suriname"=>"Suriname", "Swaziland"=>"Swaziland", "Tailândia"=>"Tailândia", "Taiwan"=>"Taiwan", "Tchecoslováquia"=>"Tchecoslováquia", "Tonga"=>"Tonga", "Trinidad e Tobago"=>"Trinidad e Tobago", "Turksccai"=>"Turksccai", "Turquia"=>"Turquia", "Tuvalu"=>"Tuvalu", "Uruguai"=>"Uruguai", "Vanuatu"=>"Vanuatu", "Wallis e Fortuna"=>"Wallis e Fortuna", "West Bank"=>"West Bank", "Yémen"=>"Yémen", "Zaire"=>"Zaire", "Zimbabwe"=>"Zimbabwe"], "Brasil") }}<br>
                  <div class="error">{{ $errors->first('photo_country') }}</div>
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="four columns alpha"><p>{{ Form::label('tags_input', 'Tags*:') }}</p></div>
              <div class="six columns">
                <p>
                  {{ Form::text('tags_input') }}
                  <button class="btn my-2" id="add_tag" style="font-size: 11px;">ADICIONAR TAG</button>
                  <br>
                  <div class="error">{{ $errors->first('tags') }}</div>
                </p>
              </div>
              <div class="five columns alpha">
                <textarea name="tags" id="tags" cols="40" rows="1" style="display: none;"></textarea>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <!-- midia-->
            </td>
          </tr>
        </table>
      </div>
      <br class="clear">
      <h4>Campos complementares</h4>
      <div class="six columns alpha row">
        <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <div class="four columns alpha"><p>{{ Form::label('photo_state', 'Estado:') }}</p></div>
            <div class="six columns omega">
              <p>
                {{ Form::select('photo_state', [""=>"Escolha o Estado", "AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá", "BA"=>"Bahia", "CE"=>"Ceará", "DF"=>"Distrito Federal", "ES"=>"Espirito Santo", "GO"=>"Goiás", "MA"=>"Maranhão", "MG"=>"Minas Gerais", "MS"=>"Mato Grosso do Sul", "MT"=>"Mato Grosso", "PA"=>"Pará", "PB"=>"Paraíba", "PE"=>"Pernambuco", "PI"=>"Piauí", "PR"=>"Paraná", "RJ"=>"Rio de Janeiro", "RN"=>"Rio Grande do Norte", "RO"=>"Rondônia", "RR"=>"Roraima", "RS"=>"Rio Grande do Sul", "SC"=>"Santa Catarina", "SE"=>"Sergipe", "SP"=>"São Paulo", "TO"=>"Tocantins"], "") }} <br>
                <div class="error">{{ $errors->first('photo_state') }}</div>
              </p>
            </tr>
            <tr>
              <div class="four columns alpha"><p>{{ Form::label('photo_city', 'Cidade:') }}</p></div>
              <div class="six columns omega">
                <p>
                  {{ Form::text('photo_city', Input::old('photo_city')) }} <br>
                  <div class="error">{{ $errors->first('photo_city') }}</div>
                </p>
              </div>
            </tr>
            <tr>
              <div class="four columns alpha"><p>{{ Form::label('photo_district', 'Bairro:') }}</p></div>
              <div class="six columns omega">
                <p>
                  {{ Form::text('photo_district', Input::old('photo_district')) }} <br>
                </p>
              </div>
            </tr>
            <tr>
              <div class="four columns alpha"><p>{{ Form::label('photo_street', 'Endereço:') }}</p></div>
              <div class="six columns omega">
                <p>
                  {{ Form::text('photo_street', Input::old('photo_street')) }} <br>
                </p>
              </div>
            </tr>
            <tr>
              <div class="four columns alpha"><p>Adicione a um álbum:</p></div>
              <div class="six columns omega" style="white-space : nowrap;">
                <p>
                  <div class="btn" onclick="newAlbumInput()" style="font-size: 11px; width: 76px; display: inline-block">NOVO ÁLBUM</div>
                  <div class="btn" onclick="selectAlbumInput()" style="font-size: 11px; width: 104px; display: inline-block">ESCOLHER ÁLBUM</div>
                </p>
              </div>
            </tr>
            <tr>
              <?php
                $albuns[""] = "Escolha o album";
                foreach (Auth::user()->albums as $k => $album) {
                  if ($album->institution_id == null)
                    $albuns[$album->id]	= $album->title;
                }
              ?>
              <div class="four columns alpha select-album" style="display: none"><p>{{ Form::label('photo_album', 'Adicionar ao álbum:') }}</p></div>
              <div class="six columns omega">
                <p class="select-album" style="display: none;">
                  {{ Form::select('photo_album', $albuns, "") }} <br>
                </p>
              </div>
            </tr>
            <tr>
              <div class="four columns alpha new-album-name" style="display: none"><p>{{ Form::label('new_album-name', 'Digite o nome do novo álbum:') }}</p></div>
              <div class="six columns omega">
                <p class="new-album-name" style="display: none;">
                  {{ Form::text('new_album-name', Input::old('new_album-name')) }} <br>
                </p>
              </div>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
<script type="text/javascript">
  function newAlbumInput() {
    var select_album = document.getElementsByClassName('select-album');
    var new_album = document.getElementsByClassName('new-album-name');
    var i;
    for (i = 0; i < select_album.length; i++) {
      select_album[i].style.display = "none";
    }
    for (i = 0; i < new_album.length; i++) {
      new_album[i].style.display = "block";
    }
    document.getElementById("photo_album").value = "";
  }
  function selectAlbumInput() {
    var select_album = document.getElementsByClassName('select-album');
    var new_album = document.getElementsByClassName('new-album-name');
    var i;
    for (i = 0; i < select_album.length; i++) {
      select_album[i].style.display = "block";
    }
    for (i = 0; i < new_album.length; i++) {
      new_album[i].style.display = "none";
    }
    document.getElementById("new_album-name").value = "";
  }
</script>
</div>

<div>
  <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> <td>
        <div class="two columns alpha"><p>{{ Form::label('photo_imageDate', 'Data da imagem:') }}</p></div>
        <div class="three columns omega">
          <p>{{ Form::text('photo_imageDate','',array('id' => 'datePickerImageDate','placeholder'=>'DD/MM/AAAA')) }}
            <span class="space_txt_element">Não sabe a data precisa?
              <a onclick="date_visibility('date_img_inaccurate');" >Clique aqui.</a>
            </span>
            <br> <div id="error_image_date" class="error">{{ $errors->first('photo_imageDate') }}</div>
          </p>
          <p>
            <div id="date_img_inaccurate" style="display:none;">
              @include('photos.includes.dateImage')
            </div>
            <label id="answer_date_image" class="resultDateWork"></label>
          </p>
        </div>
      </td>
    </tr>

    <tr><td></td></tr>
    <tr><td> @include('photos.includes.workAuthor') </td></tr>
    <tr><td></td></tr>
    <tr><td>
    <div class="two columns alpha"><p>{{ Form::label('photo_workDate', 'Ano de conclusão da obra:' ) }}</p></div>
    <div class="three columns omega">
      <p>
        @include('photos.includes.dateList')
        <span class="space_txt_element">Não sabe a data precisa?
          <a onclick="date_visibility('otherDate');" >Clique aqui.</a>
        </span>
      </p>
      <p>
        <div id="otherDate" style="display:none;">
          @include('photos.includes.dateWork')
        </div>
        <label id="answer_date" class="resultDateWork"></label>
      </p>
    </div>
  </td>
</tr>
<tr>
  <td>
  </td>
</tr>
<tr>
  <td>
    <div class="two columns alpha"><p>{{ Form::label('photo_description', 'Descrição:') }}</p></div>
    <div class="three columns omega">
      <p>
        {{ Form::textarea('photo_description', Input::old('photo_description'),['size' =>'36x7']) }} <br>

      </p>
    </div>
  </td>
</tr>
</table>
</div>
<div class="twelve columns omega row">
  <h4>Licença</h4>
  <div class="form-group">
    *{{ Form::checkbox('photo_authorization_checkbox', 1, true, ['id' => 'photo_authorization_checkbox']) }}
    {{ Form::label('photo_authorization_checkbox', '&nbsp;Sou o autor da imagem ou possuo permissão expressa do autor para disponibilizá-la no Arquigrafia')}}
    <br><div class="error">{{ $errors->first('photo_authorization_checkbox') }}</div>
  </div>
</div>
<div class="twelve columns omega row">
  <label for="terms" generated="true" class="error" style="display: inline-block; "></label>
  Escolho a licença <a href="http://creativecommons.org/licenses/?lang=pt_BR" id="creative_commons" target="_blank" style="text-decoration:underline; line-height:16px;">Creative Commons</a>, para publicar a imagem, com as seguintes permissões:
</div>
<div class="four columns" id="creative_commons_left_form">
  Permitir o uso comercial da imagem?
  <br>
  <div class="form-row">
    <input type="radio" name="photo_allowCommercialUses" value="YES" id="photo_allowCommercialUsesYES" checked="checked">
    <label for="photo_allowCommercialUsesYES">Sim</label><br class="clear">
  </div>
  <div class="form-row">
    <input type="radio" name="photo_allowCommercialUses" value="NO" id="photo_allowCommercialUsesNO">
    <label for="photo_allowCommercialUsesNO">Não</label><br class="clear">
  </div>
</div>
<div class="four columns" id="creative_commons_right_form">
  Permitir modificações em sua imagem?
  <br>
  <div class="form-row">
    <input type="radio" name="photo_allowModifications" value="YES" id="photo_allowModificationsYES" checked="checked">
    <label for="photo_allowModificationsYES">Sim</label><br class="clear">
  </div>
  <div class="form-row">
    <input type="radio" name="photo_allowModifications" value="YES_SA" id="photo_allowModificationsYES_SA">
    <label for="photo_allowModificationsYES_SA">Sim, contanto que os outros compartilhem de forma semelhante</label><br class="clear">
  </div>
  <div class="form-row">
    <input type="radio" name="photo_allowModifications" value="NO" id="photo_allowModificationsNO">
    <label for="photo_allowModificationsNO">Não</label><br class="clear">
  </div>
</div>
<div class="twelve columns">
  <input name="enviar" type="submit" class="btn" value="ENVIAR">
  <div id="dialog-confirm" title=" "></div>
</div>
</div>
{{ Form::close() }}

</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    var typeChecked  = "{{Input::old('type')}}";

    if(typeChecked == "video" ) {
      document.getElementById('type_video').checked = true;
      $('#divVideo').show();
      $('#divPhoto').hide();
    } else {
      document.getElementById('type_photo').checked = true;
      document.getElementById('video').value = null;
      $('#divVideo').hide();
      $('#divPhoto').show();
    }

    $('input[type=radio][name=type]').change(function(){
      if(this.value == "video"){
        $('#divVideo').show();
        $('#divPhoto').hide();
      } if(this.value == "photo") {
        $('#divVideo').hide();
        $('#divPhoto').show();
      }
    });

    if({{Input::old('autoOpenModal','false')}}) {
      $( "#dialog-confirm" ).html("<b>Cadastro de imagem realizado com sucesso!</b> <br><br> Gostaria de utilizar os dados da imagem cadastrada para o próximo upload?");
      $( "#dialog-confirm" ).dialog({
          resizable: false,
          height:180,
          modal: true,
          buttons: {
              "Sim": function() {
                  $( this ).dialog( "close" );
              },
              "Não": function() {
                  //$( this ).dialog( "close" );
                  window.location.replace('{{ URL::to("/") }}/photos/{{Input::old('photoId')}}');
              }
          }
      });
    }

    var radio_checked_commercial    = "{{"photo_allowCommercialUses".(Input::old('photo_allowCommercialUses'))}}";
    var radio_checked_modifications = "{{"photo_allowModifications".(Input::old('photo_allowModifications'))}}";

    if((radio_checked_commercial != "photo_allowCommercialUses") && (radio_checked_modifications != "photo_allowModifications")) {
      if(radio_checked_commercial != "photo_allowCommercialUsesYES") {
        document.getElementById("photo_allowCommercialUsesYES").checked = false;
        document.getElementById(radio_checked_commercial).checked       = true;
      }
      if(radio_checked_modifications != "photo_allowModificationsYES") {
        document.getElementById("photo_allowModificationsYES").checked = false;
        document.getElementById(radio_checked_modifications).checked   = true;
      }
    }

    $('#tags').textext({ plugins: 'tags' });
    @if(Input::old('tags')!=null)
      <?php $tags = explode (",", Input::old('tags')); ?>
    @endif

    @if (isset($tags) && $tags !=null)
      @foreach ( $tags as $tag )
          $('#tags').textext()[0].tags().addTags([ {{ '"' . $tag . '"' }} ]);
      @endforeach
    @endif

    $('#add_tag').click(function(e) {
      e.preventDefault();
      var tag = $('#tags_input').val();
      if (tag == '') return;
      $('#tags').textext()[0].tags().addTags([ tag ]);
      $('#tags_input').val('');
    });
    $('#tags_input').keypress(function(e) {
      var key = e.which || e.keyCode;
      if (key == 44 || key == 46 || key == 59)
        e.preventDefault();
    });

    // authors
    $('#work_authors').textext({ plugins: 'tags' });

    @if(Input::old('work_authors')!= null)
      <?php //print_r(Input::old('work_authors'));
      $work_authors = explode (";", Input::old('work_authors')); ?>
    @endif

    @if (isset($work_authors) && $work_authors != null)
      @foreach ( $work_authors as $work_author )
        $('#work_authors').textext()[0].tags().addTags([ {{ '"' . $work_author . '"' }} ]);
      @endforeach
    @endif

    $('#add_work_authors').click(function(e) {
      e.preventDefault();
      authorsList();
    });
    $('#photo_workAuthor').keypress(function(e) {
      var key = e.which || e.keyCode;
      //alert("A" +key)
      if(key ==13)
        authorsList();

      if (key == 44 || key == 46 || key == 59) // key = , ou Key = . ou key = ;
        e.preventDefault();
    });

    function authorsList(){
      var authors = $('#photo_workAuthor').val();
      if (authors == '') return;
      $('#work_authors').textext()[0].tags().addTags([ authors ]);
      $('#photo_workAuthor').val('');
    }

    @if( Input::old('century_image'))
      var centuryInputImage = "{{Input::old('century_image')}}";
      showPeriodCenturyImage(centuryInputImage);
      retrieveCenturyImage(centuryInputImage);
    @endif

    @if( Input::old('decade_select_image'))
      var decadeInputImage = "{{Input::old('decade_select_image')}}";
      retrieveDecadeImage(decadeInputImage);
      getCenturyOfDecade(decadeInputImage,"imageDate");
    @endif

    @if( Input::old('century'))
      var centuryInput = "{{Input::old('century')}}";
      showPeriodCentury(centuryInput);
      retrieveCentury(centuryInput);
    @endif

    @if( Input::old('decade_select'))
      var decadeInput = "{{Input::old('decade_select')}}";
      retrieveDecade(decadeInput);
      getCenturyOfDecade(decadeInput,"workDate");
    @endif

    @if($dates == false)
      window.onload = cleanToLoad;
    @endif

    if({{Input::old('dates','true')}}){
      if("{{Input::old('century')}}" != "" || "{{Input::old('decade_select')}}" != "" ){
        window.onload = resultSelectDateWork("otherDate");
      }
    }

    if({{Input::old('dateImage','true')}}){
      if("{{Input::old('century_image')}}" != "" || "{{Input::old('decade_select_image')}}" != "" ){
        window.onload = resultSelectDateWork("date_img_inaccurate");
      }
    }

});

$(function() {
  $( "#datePickerImageDate" ).datepicker({
    dateFormat:'dd/mm/yy'
  });
});
</script>
@stop
