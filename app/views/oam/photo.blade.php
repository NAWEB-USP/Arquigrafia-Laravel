@extends('layouts.oam')

@section('content')

  <section class="mt-3">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1>{{ $photo->name }}</h1>
          <br>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <img src="/arquigrafia-images/{{ $photo->id }}_view.jpg" width="100%" alt="">
        </div>
      </div>

    	<h2 class="mt-3">Audios</h1>

      <div id="control">
    		<button id="record" class="btn"><i class="material-icons">mic</i></button>
    	</div>

      <div class="row">
        <div class="col-12">
          @foreach($audios as $audio)
            <div class="player my-3 p-2 shadow">
              <div class="row">
                <div class="col-2">
                  <i id="play-{{ $audio->id }}" class="material-icons play" data-status="stop" data-id="{{ $audio->id }}" data-file="{{ $audio->file }}">play_circle_filled</i>
                </div>
                <div class="col-10 pt-1">
                  <div class="time"><strong id="audio-time-{{ $audio->id }}">00:00</strong> | <span id="audio-total-{{ $audio->id }}">00:00</span></div>
                  <div class="user">{{ $audio->user->name }} | {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $audio->created_at)->format('d/m/Y'); }}</div>
                </div>
              </div>
            </div>
          @endforeach
          @if(count($audios) < 1)
            <div class="row">
              <div class="col-10">
                <p>Deixe suas impressões com comentários em áudio.</p>
              </div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </section>

  <p>&nbsp;</p>

@stop

@section('scripts')

  <script type="text/javascript">
    window.arquigrafia = {};
    window.arquigrafia.user = {{ $user->id }};
    window.arquigrafia.photo = {{ $photo->id }};
  </script>
  <script src="/js/audio.js" type="module"></script>
  <script type="text/javascript">

    var audios = [];

    $('.player .play').click(function(e) {
      var id = $(this).data('id');
      var status = $(this).data('status');

      if (status == "stop") {
        $(this).data('status', 'playing');
        $(this).html('pause_circle_filled');
        var url = '/audios/' + $(this).data('file');
        var audio = new Audio(url);
        audio.id = id;
        audios[id] = audio;
        audio.play();
        audio.onended = function() {
          $(this).data('status', 'stop');
          $("#play-" + this.id).html('play_circle_filled');
        };
      } else {
        $(this).data('status', 'stop');
        $(this).html('play_circle_filled');
        audios[id].pause();
      }

    });

  </script>

@stop
