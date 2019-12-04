@extends('layouts.oam')

@section('content')

  <section>
    <div class="row no-gutters">
      <div class="col-12 p-0 map">
        <div id="map"></div>
      </div>
    </div>
  </section>

  <script>
    // Initialize and add the map
    function initMap() {
      // The location of USP
      // -23.5594163,-46.7331145
      var usp = {lat: -23.5594163, lng: -46.7331145};
      // -23.5602929,-46.7297912
      var fau = {lat: -23.5602929, lng: -46.7297912};
      // -23.5609413,-46.7281307
      var fea = {lat: -23.5587404, lng: -46.7289977};
      // -23.5609413,-46.7281307
      var eca = {lat: -23.5582457, lng: -46.7267433};
      // -23.5609413,-46.7281307
      var geo = {lat: -23.5617959, lng: -46.7277503};
      // -23.5609413,-46.7281307
      var fflch = {lat: -23.5615552, lng: -46.7314305};
      // 23.5587102,
      var praca = {lat: -23.5595485, lng: -46.7242796};
      // -23.558358103840156,-46.7258651893718
      var crusp = {lat: -23.5573779, lng: -46.7202907};
      // -23.5624686,-46.7217269
      var biblioteca = {lat: -23.5624686, lng: -46.7217269};
      // -23.5609413,-46.7281307
      var geografia = {lat: -23.5632388, lng: -46.7233488};
      // The map, centered at USP
      var map = new google.maps.Map(document.getElementById('map'), {zoom: 14, center: usp});
      // The marker, positioned at USP
      var markers = [];
      markers.push({marker: new google.maps.Marker({position: fau, map: map, icon: '/img/icons/pin.png'}), query: "rua do Lago, 876"});
      // SELECT * FROM arquigrafia.photos WHERE street like "Avenida Professor Lúcio Martins Rodrigues%";
      markers.push({marker: new google.maps.Marker({position: eca, map: map, icon: '/img/icons/pin.png'}), query: "Avenida Professor Lúcio Martins Rodrigues%"});
      // Praça Do Relógio
      markers.push({marker: new google.maps.Marker({position: praca, map: map, icon: '/img/icons/pin.png'}), query: "Praça Do Relógio"});
      // Rua Do Anfiteatro
      // markers.push({marker: new google.maps.Marker({position: crusp, map: map, icon: '/img/icons/pin.png'}), query: "Rua Do Anfiteatro"});
      // brasiliana R. Da Biblioteca - ["R. Da Biblioteca","Rua Da Biblioteca"]
      markers.push({marker: new google.maps.Marker({position: biblioteca, map: map, icon: '/img/icons/pin.png'}), query: "R. Da Biblioteca"});
      // geografia
      markers.push({marker: new google.maps.Marker({position: geografia, map: map, icon: '/img/icons/pin.png'}), query: "Avenida Professor Lineu Prestes"});

      markers.forEach(function(place){
        place.marker.addListener('click', function() {
          window.location = "/oam/place?street=" + place.query;
        });
      });

    }
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbz3tZhIT10vx_AqIYiiAyBLdMVRsa5ts&callback=initMap"></script>

@stop
