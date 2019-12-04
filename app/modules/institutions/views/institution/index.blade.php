@extends('layouts.default')
{{ HTML::style('css/inst.index.css'); }}
{{-- <!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head> --}}


    @section('content')
        {{-- <body> --}}
        <h1> INSTITUIÇÕES </h1>
        <table style="width:100%">
        @if(count($institutions) > 0)
            @foreach($institutions as $institution)
                <tr>
                    <td><ul>{{$institution->name}}</ul></td>
                    <td><ul>{{$institution->photo->id}}</ul></td>
                    <td><ul>{{$institution->site}}</ul></td>
                </tr>
            @endforeach
        @else
            <p>No institutions to see here.</p>
        @endif
    </table>
    {{-- <body> --}}
    @endsection
</html>