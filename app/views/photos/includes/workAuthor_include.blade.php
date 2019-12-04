<div class="{{$first_column}} columns alpha">
  <p>{{ Form::label('photo_workAuthor', 'Autor(es) do projeto:') }}</p>
</div>
<div class="{{ $type_field }} columns">
     <p>
           {{ Form::text('photo_workAuthor', '',array('id' => 'photo_workAuthor', 'placeholder' => 'SOBRENOME, nome')) }}
             <button class="btn my-2" id="add_work_authors" style="font-size: 11px;">ADICIONAR</button>
             <br>
             <div class="error">{{ $errors->first('work_authors') }}</div>
     </p>
</div>
<div class="six columns offset-by-two alpha">
     <textarea name="work_authors" id="work_authors" cols="60" rows="1" class="{{$size_area_author}}" style="display: none;"></textarea>
</div>
            </br>
            </br>
