@extends('layouts.default')
{{ HTML::style('css/tombos.css'); }}
{{-- <!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head> --}}


    @section('content')
        <center>
        {{-- <body> --}}
        {{$titulo = ''}}
        @if($selected != NULL)
            {{$titulo = 'de '.$selected->name}}
        @endif
        <h1> Tombos {{$titulo}}</h1>
        <table>
        {{ Form::open(['action' => 'PhotosController@filterTombos', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        {{Form::select('institution', $names)}}
        {{Form::submit('Filtrar', ['class'=>'btn btn-primary'])}}
        {{ Form::close() }}
        {{ Form::open(['action' => 'PhotosController@filterTombos', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        {{Form::text('tombo', '', ['class' => 'form-control', 'placeholder' => '0000'])}}
        {{Form::submit('Pesquisar', ['class'=>'btn btn-primary'])}}
        {{ Form::close() }}
        <a href="/tombos">Todas</a href>
        <tr>
        @if(count($photos) > 0)
            <th><ul>Tombo</ul></th>
            <th><ul>Suporte</ul></th>
            <th><ul>Completude</ul></th>
            <th><ul>Insituição</ul></th>
            @foreach($photos as $photo)
                <tr>
                    <td><ul>{{$photo->tombo}}</ul></td>
                    <td><ul>{{$photo->support}}</ul></td>
                    <td><ul>{{$photo->accepted}}</ul></td>
                    <td><ul>{{$names[$photo->institution_id]}}</ul></td>
                </tr>
            @endforeach
        @else
            <p>Não há Fotos a serem mostradas.</p>
        @endif
        </tr>
    </table>
    </center>
    {{$photos->links()}}
    {{-- <body> --}}
    @endsection
</html>