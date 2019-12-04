@extends('layouts.oam')

@section('content')

  <section class="mt-3">
    <div class="container">
      <div class="row">

        <div class="col-12">
          <h1>{{ $place }}</h1>
          <br>
          <h3>Áudios</h3>
        </div>

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
        </div>

      </div>
    </div>
  </section>

  <section class="my-3">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3>Fotos</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="grid">
            <div class="grid-sizer"></div>

            @foreach($photos as $photo)
              <a href="/oam/photo/{{ $photo->id }}"><div class="grid-item"><img src="/arquigrafia-images/{{ $photo->id }}_view.jpg" alt="" width="312px" height="240px"></div></a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <p>&nbsp;</p>

@stop

@section('scripts')

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
