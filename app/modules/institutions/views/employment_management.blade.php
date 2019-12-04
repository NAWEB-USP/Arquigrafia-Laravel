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
        <h1>Empregados</h1>
        <a href="/employment-management/create-employment">Criar novo vínculo</a href>
        <table>
        <tr>
        @if(count($employees) > 0)
            <th><ul>Empregado</ul></th>
            <th><ul>Insituição</ul></th>
            <th><ul>Cargo</ul></th>
            @foreach($employees as $e)
                <tr>
                    <td><ul>{{$e['user']}}</ul></td>
                    <td><ul>{{$e['institution']}}</ul></td>
                    <td><ul>{{$e['role']}}</ul></td>
                    <td>{{Form::open(['action' => ['modules\institutions\controllers\InstitutionsController@destroy_employment', $e['id']], 'method' => 'POST'])}}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Excluir')}}
                    {{Form::close()}}</td>
                </tr>
            @endforeach
        @else
            <p>Não há Empregados a serem mostrados.</p>
        @endif
        </tr>
    </table>
    </center>
    {{-- {{$employees->links()}} --}}
    {{-- <body> --}}
    @endsection
</html>