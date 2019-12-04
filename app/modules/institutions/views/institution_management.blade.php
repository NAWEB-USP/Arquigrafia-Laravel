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
        <h1>Instituições</h1>
        <a href="/institution-management/create">Criar nova instituição</a href>
        <table>
        <tr>
        @if(count($institutions) > 0)
            <th><ul>Insituição</ul></th>
            @foreach($institutions as $i)
                <tr>
                    <td><ul><a href="/institutions/{{$i->id}}">{{$i->name}}</a href></ul></td>
                    <td><ul><a href="/institutions/{{$i->id}}/edit">Editar</a href></ul></td>
                    <td>{{Form::open(['action' => ['modules\institutions\controllers\InstitutionsController@destroy_institution', $i->id], 'method' => 'POST'])}}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Excluir')}}
                    {{Form::close()}}</td>
                </tr>
            @endforeach
        @else
            <p>Não há Instituições a serem mostrados.</p>
        @endif
        </tr>
    </table>
    </center>
    {{-- {{$employees->links()}} --}}
    {{-- <body> --}}
    @endsection
</html>