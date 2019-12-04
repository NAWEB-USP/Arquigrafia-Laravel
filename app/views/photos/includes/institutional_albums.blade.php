  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox-edition.css" />
  <script type="text/javascript" src="{{ URL::to('/js/institutionalAlbum.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('/js/script.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('/js/stoaLogin.js') }}"></script>

    <div class="twelve columns">
          <div id="add_images" >
          <div class="eleven columns select_options add">

                <div class="seven columns alpha omega">
                  <div class="four columns alpha omega block">
                    <p class="filter"></p>
                    <br>
                  </div>
                  <!--<div class="three columns alpha omega block">
                    <p class="selectedItems"></p>
                  </div>-->
                </div>


            </div>
            <div id="add" class="eleven columns add">

              @if ($albumsInstitutional->count() > 0)

              <?php $count = 0; ?>
                  <table>
                    @foreach($albumsInstitutional as $albumInstitutional)
                      @if ($count % 2 == 0)
                       <tr>
                      @endif
                      @if ($count == 0)
                        <td width="143" class="add" >
                          <!--<span> Adicionar Album</span>
                          <img src="{{ URL::to('/img/create_album.png') }}"> -->
                          <!--<a id="newInstitutionalAlbum" href="/institutionalAlbum" id="single_view_contact_add">
                            <span> Adicionar Album</span> </a>-->
                          <a id="newInstitutionalAlbum" href="/institutionalAlbum" id="single_view_contact_add">
                          <img src="{{ URL::to('/img/create_album.png') }}">  </a>
                        </td>
                      <?php $count++; ?>
                      @endif
                      <td width="143" class="add" >
                        <div class="photo add">
                         <!-- <input type="checkbox" class="ch_photo" id="{{'albums_'.$albumInstitutional->id }}"
                          name="albums_add[]" value="{{ $albumInstitutional->id }}">-->

                          <input type="radio" class="ch_photo"  id="albums_institution"
                          name="albums_institution"  value="{{ $albumInstitutional->id }}"
                           >

                          @if ($count % 2 < 1)
                            <?php $position = 'right'; ?>
                          @else
                            <?php $position = 'left'; ?>
                          @endif

                          @if(isset($albumInstitutional->cover_id))
                            <img src="{{ URL::to('/arquigrafia-images/' . $albumInstitutional->cover_id . '_home.jpg') }}"
                            class="img_photo {{ $position }}" title="{{ $albumInstitutional->title }}">
                          @else
                            <div  style = "height:85px; width: 100%; background-color:#aaa; padding-top:4px">
                              <span>Álbum sem capa</span>
                            </div>
                          @endif
                        </div>
                      </td>
                      @if (($count+1) % 2 == 0 && $count > 0)
                       </tr>
                      @endif
                    <?php $count++ ?>
                    @endforeach
                     @if($count % 2 != 0)
                     @while($count % 2 != 0)
                       <td></td>
                        <?php $count++; ?>
                      @endwhile
                      </tr>
                     @endif
                  </table>

              @else
              <table>
                <tr>
                  <td width="143" class="add" >

                          <!--<a id="newInstitutionalAlbum" href="/institutionalAlbum" id="single_view_contact_add">
                            <span> Adicionar Album</span> </a>-->
                          <a id="newInstitutionalAlbum" href="/institutionalAlbum" id="single_view_contact_add">
                          <img src="{{ URL::to('/img/create_album.png') }}">  </a>
                  </td>
                </tr>
              </table>
              @endif
            </div>

        </div>


  </div>
<br>
<br>
<div></div>
<div id="mask"></div>
<div id="form_inst_album_window" class="container columns form window">
      <a class="close" href="#" title="FECHAR">Fechar</a>
      <div id="registInstAlbum" class="registInstAlbum">
         <br>
         <div class="four columns">
            {{ Form::open(array( 'url' => '/albums/institutionalAlbum')) }}
                <p></p>
                <br> <br>
               <div class="three columns">{{ Form::label('title','Titulo do álbum:') }}</div>
               <div class="three columns">{{ Form::text('title') }}</div>
               {{ $errors->first('title') }}

               <div class="three columns">{{ Form::label('description', 'Descrição:') }}</div>
               <div class="three columns">
               {{ Form::textarea('description',null, ['size' => '40x6'])}} </div>
               {{ $errors->first('description') }}
               <br>

               <div class="three columns">
                  <p>{{ Form::submit("CRIAR ÁLBUM",array('class'=>'btn right')) }}</p>
                  <!--<p class="error">titulo e/ou descrição vazios.</p>-->
               </div>
            {{ Form::close() }}
         </div>
      </div>
</div>
