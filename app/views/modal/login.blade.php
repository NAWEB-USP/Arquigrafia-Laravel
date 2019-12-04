@extends('layouts.default')

@section('head')
   <title>
      Arquigrafia - Entrar
   </title>
   <script type="text/javascript" src="{{ URL::to('/js/stoaLogin.js') }}"></script>
   <script type="text/javascript" src="{{ URL::to('/js/institutionLogin.js') }}"></script>
   <script type="text/javascript">
      var baseUrl = '{{ URL::to("/") }}';
   </script>
@stop

@section('content')
@if (Session::get('msgRegister'))
      <div class="container">
        <div class="twelve columns">
         <div class="four columns offset-by-four">
          <div class="message">{{ Session::get('msgRegister') }}</div>
        </div>
       </div>
      </div>
@endif
   <div class="container">
      <div class="registration">
         <!-- LOGIN SIMPLES -->

         <div class="four columns offset-by-four mb-3">
            <h1>Login</h1>

            {{ Form::open() }}

               <p>Entre com seu login ou e-mail e em seguida digite sua senha:</p>
               <p>{{ Form::label('login', 'Login ou E-mail:') }}</p>
               <p>{{ Form::text('login', '') }}</p>
               <p class="error">{{ $errors->first('login') }}</p>

               {{-- <div class="six columns alpha">{{ Form::label('login', 'Login ou E-mail:', array('class'=>'right')) }}</div>
               <div class="six columns omega">{{ Form::text('login', '', array('class'=>'right') ) }}</div>
               {{ $errors->first('login') }} --}}

              <p>{{ Form::hidden('firstTime', Session::get('msgRegister')) }}</p>
              <p>{{ Form::label('password', 'Senha:') }}</p>
              <p>{{ Form::password('password' ) }}</p>
               {{-- <div class="six columns alpha">{{ Form::label('password', 'Senha:', array('class'=>'right')) }}</div>
               <div class="six columns omega">{{ Form::password('password', array('class'=>'right') ) }}</div> --}}

              @if(Session::has('login.message'))
                <p class="error">{{ Session::pull('login.message') }}</p>
              @endif

              <p>{{ Form::submit("LOGIN",array('class'=>'btn')) }}</p>

              <p>
                <a href="{{ URL::to("/users/forget/")}}">Esqueceu sua senha?</a>
              </p>
              <p>
                <a id="institutionLogin" href="/institutionalLogin" id="single_view_contact_add">Login institucional</a>
              </p>

            {{ Form::close() }}

         </div>

         <div class="four columns offset-by-four">

            <a href="{{ $fburl }}">
               <div class="login_externo logo">
                  <img id="facebook_login" src="{{ asset('/img/Facebook_logo_square.png') }}">
               </div>
               <div class="login_externo label">
                  <p>Login com Facebook </p>
               </div>
            </a>
            <br>
            <a id="stoaLogin" href="/stoaLogin" >
               <div class="login_externo logo down">
                  <img src="{{ asset('/img/Logo-stoa.png') }}" width="75">
               </div>
               <div class="login_externo label down">
                  <p>Login com Stoa </p>
               </div>
            </a>
         </div>

      </div>
   </div>

   <div id="mask"></div>
   <div id="form_login_window" class="container form window">
      <a class="close" href="#" title="FECHAR">Fechar</a>
      <div id="registrationStoa" class="registration">
         <img src="{{ asset('/img/Logo-stoa.png') }}" class="row" style="width: 200px; display: block; margin-left: auto; margin-right: auto; ">
         <div class="four columns">
            {{ Form::open(array( 'url' => '/users/stoaLogin')) }}

               <div class="three columns">{{ Form::label('stoa_account', 'Login ou Número USP:') }}</div>
               <div class="three columns">{{ Form::text('stoa_account', '', ['class' => 'right']) }}</div>
               {{ $errors->first('login') }}

               <div class="three columns">{{ Form::label('password', 'Senha:') }}</div>
               <div class="three columns">{{ Form::password('password', ['class' => 'right']) }}</div>
               <br>

               <div class="three columns">
                  <p>{{ Form::submit("LOGIN",array('class'=>'btn right')) }}</p>
                  <p class="error">Número USP e/ou senha inválidos.</p>
               </div>
            {{ Form::close() }}
         </div>
      </div>
   </div>
   <div id="form_login_inst_window" class="container form window">
      <a class="close" href="#" title="FECHAR">Fechar</a>
      <div id="registrationInstitution" class="registrationInstitution">
         <img src="{{ asset('/img/logo.png') }}" class="row" style="width: 200px; display: block; margin-left: auto; margin-right: auto; ">
         <br>
         <div class="four columns">
            {{ Form::open(array( 'url' => '/users/institutionalLogin')) }}


               <div class="three columns">{{ Form::label('institution', 'Acervo:') }}</div>
               <div class="three columns">

                  @if(!is_null($institutions))
                    {{ Form::select('institution', $institutions , Input::old('institution')) }}
                  @else
                      {{ Form::select('institution', [""=>"Escolha o acervo institutional"], "",array('class' => 'left')) }}
                  @endif
               </div>
		<br>

		 <div class="three columns">{{ Form::label('login', 'Login ou E-mail:') }}</div>
               <div class="three columns">{{ Form::text('login', '', ['class' => 'left']) }}</div>
               {{ $errors->first('login') }}


               <div class="three columns">{{ Form::label('password', 'Senha:') }}</div>
               <div class="three columns">{{ Form::password('password', ['class' => 'left']) }}</div>
               <br>
               <br>
               <div class="three columns">
                  <p>{{ Form::submit("LOGIN",array('class'=>'btn right')) }}</p>
                  <p class="error">Usuário e/ou e-mail e/ou instituição e/ou senha inválidos.</p>
               </div>
            {{ Form::close() }}
         </div>
      </div>
   </div>
@stop
