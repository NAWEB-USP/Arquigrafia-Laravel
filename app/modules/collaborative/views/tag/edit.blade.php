@extends('layouts.default')
    @section('content')
    <h1>Editar</h1>
    {{ Form::open(['action' => ['modules\collaborative\controllers\TagsController@update', $tagVec[0]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="form-group">
            {{Form::label('name', 'Nome')}}
            {{Form::text('name', $tagVec[0]->name, ['class' => 'form-control', 'placeholder' => 'Nome'])}}
        </div>

        <div class="form-group">
            {{Form::label('descricao', 'Descrição')}}
            {{Form::text('description', $tagVec[0]->description, ['class' => 'form-control', 'placeholder' => 'Descrição'])}}
        </div>

        <div class="form-group">
            {{Form::label('equivalencia', 'Equivalência')}}
            {{Form::text('is', $tagVec[1], ['class' => 'form-control', 'placeholder' => 'Equivalência'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_1', 'Categoria - Level 1')}}
            {{Form::text('cat_1', $tagVec[0]->cat_1, ['class' => 'form-control', 'placeholder' => 'Categoria 1'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_2', 'Categoria - Level 2')}}
            {{Form::text('cat_2', $tagVec[0]->cat_2, ['class' => 'form-control', 'placeholder' => 'Categoria 2'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_3', 'Categoria - Level 3')}}
            {{Form::text('cat_3', $tagVec[0]->cat_3, ['class' => 'form-control', 'placeholder' => 'Categoria 3'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_4', 'Categoria - Level 4')}}
            {{Form::text('cat_4', $tagVec[0]->cat_4, ['class' => 'form-control', 'placeholder' => 'Categoria 4'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_5', 'Categoria - Level 5')}}
            {{Form::text('cat_5', $tagVec[0]->cat_5, ['class' => 'form-control', 'placeholder' => 'Categoria 5'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_6', 'Categoria - Level 6')}}
            {{Form::text('cat_6', $tagVec[0]->cat_6, ['class' => 'form-control', 'placeholder' => 'Categoria 6'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_6', 'Categoria - Level 6')}}
            {{Form::text('cat_6', $tagVec[0]->cat_6, ['class' => 'form-control', 'placeholder' => 'Categoria 6'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_7', 'Categoria - Level 7')}}
            {{Form::text('cat_7', $tagVec[0]->cat_7, ['class' => 'form-control', 'placeholder' => 'Categoria 7'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_8', 'Categoria - Level 8')}}
            {{Form::text('cat_8', $tagVec[0]->cat_8, ['class' => 'form-control', 'placeholder' => 'Categoria 8'])}}
        </div>

        <div class="form-group">
            {{Form::label('cat_9', 'Categoria - Level 9')}}
            {{Form::text('cat_9', $tagVec[0]->cat_9, ['class' => 'form-control', 'placeholder' => 'Categoria 9'])}}
        </div>

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {{ Form::close() }}
    @endsection