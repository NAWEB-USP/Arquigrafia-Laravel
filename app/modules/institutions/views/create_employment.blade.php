@extends('layouts.default')
@section('content')
    <h1>Criar novo vínculo</h1>
    {{ Form::open(['action' => 'modules\institutions\controllers\InstitutionsController@store_employment', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="form-group">
            {{Form::label('name', 'Login do usário')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Login do usário'])}}
        </div>
        <div class="form-group">
            {{Form::label('name', 'Instituição')}}
            {{Form::select('institution', $inst_names)}}
        </div>
        <div class="form-group">
            {{Form::label('name', 'Cargo')}}
            {{Form::select('role', $role_names)}}
        </div>

        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {{ Form::close() }}
@endsection