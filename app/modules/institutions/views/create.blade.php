@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>

  <script type="text/javascript" src="{{ URL::to("/") }}/js/textext.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.css" />

  <link rel="stylesheet" href="{{ URL::to("/") }}/css/jquery-ui/jquery-ui.min.css">
  <script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
@stop

@section('content')

  <div class="container">
    {{ Form::open(['action' => 'modules\institutions\controllers\InstitutionsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

    <div id="registration">

      <div class="twelve columns">
        <h1>Criar perfil da instituição</h1>
        <p>
        <div>
          <img class="avatar profile-picture" src="{{ asset("img/avatar-institution.png") }}" width="60" height="60" class="user_photo_thumbnail"/>
        </div>
        </p>
      </div>
      <p>
      </p>

      <div class="four columns">

        <div class="twelve columns row step-1">


        <div class="four columns alpha">
          <p>{{ Form::label('photo','Alterar foto:') }}
          {{ Form::file('photo', array('id'=>'imageUpload', 'onchange' => 'readURL(this);')) }}</p>
          <br>
          <img src="" id="preview_photo">
          <br>
        </div>
      </div>

      <script type="text/javascript">
        function importPicture() {
          $.get("/getPicture")
            .done(function( data ) {
               {
                $('.profile-picture').attr('src', data);
              }
            });
        }
      </script>

     <script type="text/javascript">
        function readURL(input) {
          $("#preview_photo").hide();
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#preview_photo')
                .attr('src', e.target.result)
                .width(200);
                $("#preview_photo").show();
            };
            reader.readAsDataURL(input.files[0]);
          }
        }
      </script>


          <p>(*) Campos obrigatórios.</p>
          <br />

          <div class="two columns alpha"><p>{{ Form::label('name', 'Nome completo*:') }}</p></div>
          <div class="two columns omega">
            <p>{{Form::text('name_institution', '')}} <br>
            <div class="error">{{ $errors->first('name_institution') }} </div>
            </p>
          </div>
          <div class="two columns alpha"><p>{{ Form::label('acronimo', 'Sigla ou acrônimo:') }}</p></div>
          <div class="two columns omega">
            <p>{{Form::text('acronym_institution', '')}} <br>
            <div class="error">{{ $errors->first('acronym_institution') }} </div>
            </p>
          </div>

          <div class="two columns alpha"><p>{{ Form::label('email', 'E-mail*:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('email', '') }} <br>
              <div class="error">{{ $errors->first('email') }} </div>
           <!-- <input name="visibleEmail" type="checkbox" value="yes" {{-- $institution->visibleEmail == 'yes' ? "checked" : "" --}}>Visível no perfil público<br> -->
            </p>
          </div>

        <div class="two columns alpha"><p>{{ Form::label('country', 'País:') }}</p></div>
        <div class="two columns omega">
          <p>{{ Form::select('country', [ "Afeganistão"=>"Afeganistão", "África do Sul"=>"África do Sul", "Albânia"=>"Albânia", "Alemanha"=>"Alemanha", "América Samoa"=>"América Samoa", "Andorra"=>"Andorra", "Angola"=>"Angola", "Anguilla"=>"Anguilla", "Antartida"=>"Antartida", "Antigua"=>"Antigua", "Antigua e Barbuda"=>"Antigua e Barbuda", "Arábia Saudita"=>"Arábia Saudita", "Argentina"=>"Argentina", "Aruba"=>"Aruba", "Australia"=>"Australia", "Austria"=>"Austria", "Bahamas"=>"Bahamas", "Bahrain"=>"Bahrain", "Barbados"=>"Barbados", "Bélgica"=>"Bélgica", "Belize"=>"Belize", "Bermuda"=>"Bermuda", "Bhutan"=>"Bhutan", "Bolívia"=>"Bolívia", "Botswana"=>"Botswana", "Brasil"=>"Brasil", "Brunei"=>"Brunei", "Bulgária"=>"Bulgária", "Burundi"=>"Burundi", "Cabo Verde"=>"Cabo Verde", "Camboja"=>"Camboja", "Canadá"=>"Canadá", "Chade"=>"Chade", "Chile"=>"Chile", "China"=>"China", "Cingapura"=>"Cingapura", "Colômbia"=>"Colômbia", "Djibouti"=>"Djibouti", "Dominicana"=>"Dominicana", "Emirados Árabes"=>"Emirados Árabes", "Equador"=>"Equador", "Espanha"=>"Espanha", "Estados Unidos"=>"Estados Unidos", "Fiji"=>"Fiji", "Filipinas"=>"Filipinas", "Finlândia"=>"Finlândia", "França"=>"França", "Gabão"=>"Gabão", "Gaza Strip"=>"Gaza Strip", "Ghana"=>"Ghana", "Gibraltar"=>"Gibraltar", "Granada"=>"Granada", "Grécia"=>"Grécia", "Guadalupe"=>"Guadalupe", "Guam"=>"Guam", "Guatemala"=>"Guatemala", "Guernsey"=>"Guernsey", "Guiana"=>"Guiana", "Guiana Francesa"=>"Guiana Francesa", "Haiti"=>"Haiti", "Holanda"=>"Holanda", "Honduras"=>"Honduras", "Hong Kong"=>"Hong Kong", "Hungria"=>"Hungria", "Ilha Cocos (Keeling)"=>"Ilha Cocos (Keeling)", "Ilha Cook"=>"Ilha Cook", "Ilha Marshall"=>"Ilha Marshall", "Ilha Norfolk"=>"Ilha Norfolk", "Ilhas Turcas e Caicos"=>"Ilhas Turcas e Caicos", "Ilhas Virgens"=>"Ilhas Virgens", "Índia"=>"Índia", "Indonésia"=>"Indonésia", "Inglaterra"=>"Inglaterra", "Irã"=>"Irã", "Iraque"=>"Iraque", "Irlanda"=>"Irlanda", "Irlanda do Norte"=>"Irlanda do Norte", "Islândia"=>"Islândia", "Israel"=>"Israel", "Itália"=>"Itália", "Iugoslávia"=>"Iugoslávia", "Jamaica"=>"Jamaica", "Japão"=>"Japão", "Jersey"=>"Jersey", "Kirgizstão"=>"Kirgizstão", "Kiribati"=>"Kiribati", "Kittsnev"=>"Kittsnev", "Kuwait"=>"Kuwait", "Laos"=>"Laos", "Lesotho"=>"Lesotho", "Líbano"=>"Líbano", "Líbia"=>"Líbia", "Liechtenstein"=>"Liechtenstein", "Luxemburgo"=>"Luxemburgo", "Maldivas"=>"Maldivas", "Malta"=>"Malta", "Marrocos"=>"Marrocos", "Mauritânia"=>"Mauritânia", "Mauritius"=>"Mauritius", "México"=>"México", "Moçambique"=>"Moçambique", "Mônaco"=>"Mônaco", "Mongólia"=>"Mongólia", "Namíbia"=>"Namíbia", "Nepal"=>"Nepal", "Netherlands Antilles"=>"Netherlands Antilles", "Nicarágua"=>"Nicarágua", "Nigéria"=>"Nigéria", "Noruega"=>"Noruega", "Nova Zelândia"=>"Nova Zelândia", "Omã"=>"Omã", "Panamá"=>"Panamá", "Paquistão"=>"Paquistão", "Paraguai"=>"Paraguai", "Peru"=>"Peru", "Polinésia Francesa"=>"Polinésia Francesa", "Polônia"=>"Polônia", "Portugal"=>"Portugal", "Qatar"=>"Qatar", "Quênia"=>"Quênia", "República Dominicana"=>"República Dominicana", "Romênia"=>"Romênia", "Rússia"=>"Rússia", "Santa Helena"=>"Santa Helena", "Santa Kitts e Nevis"=>"Santa Kitts e Nevis", "Santa Lúcia"=>"Santa Lúcia", "São Vicente"=>"São Vicente", "Singapura"=>"Singapura", "Síria"=>"Síria", "Spiemich"=>"Spiemich", "Sudão"=>"Sudão", "Suécia"=>"Suécia", "Suiça"=>"Suiça", "Suriname"=>"Suriname", "Swaziland"=>"Swaziland", "Tailândia"=>"Tailândia", "Taiwan"=>"Taiwan", "Tchecoslováquia"=>"Tchecoslováquia", "Tonga"=>"Tonga", "Trinidad e Tobago"=>"Trinidad e Tobago", "Turksccai"=>"Turksccai", "Turquia"=>"Turquia", "Tuvalu"=>"Tuvalu", "Uruguai"=>"Uruguai", "Vanuatu"=>"Vanuatu", "Wallis e Fortuna"=>"Wallis e Fortuna", "West Bank"=>"West Bank", "Yémen"=>"Yémen", "Zaire"=>"Zaire", "Zimbabwe"=>"Zimbabwe"], "Brasil") }}<br>
            <div class="error">{{ $errors->first('country') }} </div>
          </p>
        </div>

        <div class="two columns alpha"><p>{{ Form::label('state', 'Estado:') }}</p></div>
        <div class="two columns omega">
          <p>{{ Form::select('state', [""=>"Escolha o Estado", "AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá", "BA"=>"Bahia", "CE"=>"Ceará", "DF"=>"Distrito Federal", "ES"=>"Espirito Santo", "GO"=>"Goiás", "MA"=>"Maranhão", "MG"=>"Minas Gerais", "MS"=>"Mato Grosso do Sul", "MT"=>"Mato Grosso", "PA"=>"Pará", "PB"=>"Paraíba", "PE"=>"Pernambuco", "PI"=>"Piauí", "PR"=>"Paraná", "RJ"=>"Rio de Janeiro", "RN"=>"Rio Grande do Norte", "RO"=>"Rondônia", "RR"=>"Roraima", "RS"=>"Rio Grande do Sul", "SC"=>"Santa Catarina", "SE"=>"Sergipe", "SP"=>"São Paulo", "TO"=>"Tocantins"], '') }} <br>
            <div class="error">{{ $errors->first('state') }} </div>
          </p>
        </div>

        <div class="two columns alpha"><p>{{ Form::label('city', 'Cidade:') }}</p></div>
        <div class="two columns omega">
        <p>{{ Form::text('city', '') }}<br>
          <div class="error">{{ $errors->first('city') }} </div>
        </p>
        </div>

          <div class="two columns row alpha"><p>{{ Form::label('site', 'Site pessoal:') }}</p></div>
          <div class="two columns row omega">
            <p>{{ Form::text('site', '') }}<br>
              <div class="error">{{ $errors->first('site') }} </div>
            </p>
          </div>

          <div class="two columns alpha"><p>{{ Form::label('address', 'Endereço:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('address', '') }} <br>
              <div class="error">{{ $errors->first('address') }} </div>
            </p>
          </div>
          <div class="two columns alpha"><p>{{ Form::label('phone', 'Telefone:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('phone', '',array('id' => 'phone','placeholder'=>'(dd)(dd)dddd-dddd')) }} <br>

              <div class="error">{{ $errors->first('phone') }} </div>
            </p>
          </div>



          <div class="four columns alpha omega">
            <br>
            <a href="{{ URL::to('/institution-management') }}" class='btn right'>VOLTAR</a>
            {{ Form::submit("SALVAR", array('class'=>'btn right')) }}

          </div>


        {{ Form::close() }}

        <p>&nbsp;</p>

      </div>

    </div>

  </div>
<script type="text/javascript">
//msy
    $(function() {

    $( "#datePickerBirthday" ).datepicker({
      dateFormat:'dd/mm/yy'
    }
      );
    });

    function password_change(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none'){
          e.style.display = 'block';
       }else{
          e.style.display = 'none';
        }


}
</script>
@stop
