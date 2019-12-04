<html>
@extends('layouts.default')

    <head>
        <title>Relatório de Tags</title>
        <meta charset="UTF-8">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>

    @section('content')
        <h1> TAGS </h1>
        <a href="/tags/create">Clique aqui para criar uma nova tag de acervo</a href>
        <hr>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                <h2>Tags de Acervo</h2>
                <div style="height:500px;width:500px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;display: inline-block;">
                    <table style="twidth:100%" class="table table-striped">
                    <tbody>
                    @if(count($tags_a) > 0)
                        @foreach($tags_a as $tag)
                            <tr>
                            <td><ul>{{$tag->name}}</ul></td>
                            @if($tag->is != 0 and $tags_a->find($tag->is) != NULL)
                                <td>Equivale a {{$tags_a->find($tag->is)->name}}</td>
                            @else
                                <td></td>
                            @endif
                        <td><a href="/tags/{{$tag->id}}/edit">Editar</a href></td>
                        <td>{{Form::open(['action' => ['modules\collaborative\controllers\TagsController@destroy', $tag->id], 'method' => 'POST'])}}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Excluir')}}
                        {{Form::close()}}</td>
                        </tr>
                        @endforeach
                    @else
                        <p>Não há tags de acervo para mostrar.</p>
                    @endif
                    </tbody>
                    </table>
                </div>
                </div>
                <div class="col-sm-6">
                <h2>Tags de Usuário</h2>
                <div style="height:500px;width:500px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;display: inline-block;">
                    <table style="width:100%" class="table table-striped">
                    <tbody>
                    @if(count($tags_u) > 0)
                        @foreach($tags_u as $tag)
                            <tr>
                            <td><ul>{{$tag->name}}</ul></td>
                            </tr>
                        @endforeach
                    @else
                        <p>Não há tags de usuário para mostrar.</p>
                    @endif
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    @endsection
</html>