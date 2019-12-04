<?php
namespace modules\institutions\controllers;
use lib\date\Date;
use lib\utils\HelpTool;
use modules\institutions\models\Institution;
use modules\institutions\models\Employee as Employee;
use modules\collaborative\models\Tag;
use Illuminate\Support\Facades\Validator;
use Role;
use User;
use Session;
use Auth;
use Photo;
use News;
use Author;
use Album;
use Carbon\Carbon;
use Image;
use URL;

class InstitutionsController extends \BaseController {
  protected $date;

  public function __construct(Date $date = null) {
    $this->date = $date ?: new Date; 
  }

  public function index() {
    $institutions = Institution::all();
    return \View::make('institution_management', [
      'institutions' => $institutions
    ]);
  }

  public function show($id) {
    $institution = Institution::find($id);
    if ( is_null($institution) ) {
      return \Redirect::to('/home');
    }
    $photos = $institution->photos()->limit(100)->get()->reverse();

    $follow = null;
    $responsible = false;

    if ( !Session::has('institutionId') && Auth::check() ) {
      $user = Auth::user();
      $follow = true;
      if ($user->followingInstitution->contains($institution->id)) {
        $follow = false;
      }
    }
    if (Auth::check() && $institution->id == Session::get('institutionId')) {
      $userId = Auth::id();
      $responsibleEmployee = Institution::RoleOfInstitutionalUser($userId);
      if (!empty($responsibleEmployee)) {
        $responsible = true;
      }
    }
    $drafts = Photo::withInstitution($institution)->onlyDrafts()->paginate(50);

    return \View::make('show-institution', [
      'institution' => $institution,
      'photos' => $photos,
      'follow' => $follow,
      'responsible' => $responsible,
      'drafts' => $drafts
    ]);
  }

  /*Criar nova instituição*/
  public function create() {
    $user = \Auth::user();
    if (!Session::has('institution_id') and $user->admin == False) {
      return \Redirect::to('/home');
    }
    return \View::make('create');
  }

  /*Guarda dados da instituição*/
  public function store() {
    $user = \Auth::user();
    if (!Session::has('institution_id') and $user->admin == False) {
      return \Redirect::to('/home');
    }
    $institution = new Institution;
    \Input::flash();
    $input = \Input::only('name_institution', 'email', 'site', 'country', 'state', 'city',
      'photo', 'address', 'phone','acronym_institution');
    $rules = array('name_institution' => 'required',
      "site" => "url",
      "phone" => "regex:/^[0-9-() ]{8,25}$/");

    if ($input['email'] !== $institution->email)
      $rules['email'] = 'required|email|unique:institutions';

    $validator = \Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return \Redirect::to('/institution-management/create')->withErrors($messages);
    } else {
      $institution->name = trim($input['name_institution']);
      $institution->email = $input['email'];
      $institution->country = $input['country'];

      if(!empty($input['acronym_institution']))
        $institution->acronym = trim($input['acronym_institution']);
      else
        $institution->acronym = null; 

      if(!empty($input['site']))
        $institution->site = trim($input['site']);
      else
        $institution->site = null;

      if(!empty($input['state']))
        $institution->state = $input['state'];
      else
        $institution->state = null; 

      if(!empty($input['city']))
        $institution->city = trim($input['city']);
      else
        $institution->city = null;

      if(!empty($input['address']))
        $institution->address = trim($input['address']);
      else
        $institution->address = null;

      if(!empty($input['phone']))
        $institution->phone = trim($input['phone']);
      else
        $institution->phone = null;

      $institution->touch();
      $institution->save();

      if (\Input::hasFile('photo') and \Input::file('photo')->isValid())  {
        $file = \Input::file('photo');
        $ext = $file->getClientOriginalExtension();
        $institution->photo = "/arquigrafia-avatars-inst/".$institution->id.".jpg";
        $institution->save();
        $image = Image::make(\Input::file('photo'))->encode('jpg', 80);
        $image->save(public_path().'/arquigrafia-avatars-inst/'.$institution->id.'.jpg');
        $file->move(public_path().'/arquigrafia-avatars-inst', $institution->id."_original.".strtolower($ext));
      }
      static::updateHeaderInstitution($institution);

      return \Redirect::to("/institution-management")->with('message', '<strong>Criação de perfil da instituição</strong><br>Instituição criada com sucesso');
    }

  }

  /*Editar dados da instituição*/
  public function edit($id) {
    $user = \Auth::user();
    if (!Session::has('institution_id') and $user->admin == False) {
      return \Redirect::to('/home');
    }

    $institution = Institution::find($id); 
    if ( is_null($institution) )   return Redirect::to('/home');

    return \View::make('edit', [
      'institution' => $institution
    ]);
  }

  public function destroy_institution($id) {
    $user = \Auth::user();
    if (!Session::has('institution_id') and $user->admin == False) {
      return \Redirect::to('/home');
    }
    try {
      Institution::find($id)->delete();
    }
    finally {
      return \Redirect::to('/institution-management/');
    }
  }

  /*Update dados da instituição*/
  public function update($id) {
    $institution = Institution::find($id);
    \Input::flash();
    $input = \Input::only('name_institution', 'email', 'site', 'country', 'state', 'city',
      'photo', 'address', 'phone','acronym_institution');
    $rules = array('name_institution' => 'required',
      "site" => "url",
      "phone" => "regex:/^[0-9-() ]{8,25}$/");

    if ($input['email'] !== $institution->email)
      $rules['email'] = 'required|email|unique:institutions';

    $validator = \Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return \Redirect::to('/institutions/' . $id . '/edit')->withErrors($messages);
    } else {
      $institution->name = trim($input['name_institution']);
      $institution->email = $input['email'];
      $institution->country = $input['country'];

      if(!empty($input['acronym_institution']))
        $institution->acronym = trim($input['acronym_institution']);
      else
        $institution->acronym = null; 

      if(!empty($input['site']))
        $institution->site = trim($input['site']);
      else
        $institution->site = null;

      if(!empty($input['state']))
        $institution->state = $input['state'];
      else
        $institution->state = null; 

      if(!empty($input['city']))
        $institution->city = trim($input['city']);
      else
        $institution->city = null;

      if(!empty($input['address']))
        $institution->address = trim($input['address']);
      else
        $institution->address = null;

      if(!empty($input['phone']))
        $institution->phone = trim($input['phone']);
      else
        $institution->phone = null;

      $institution->touch();
      $institution->save();

      if (\Input::hasFile('photo') and \Input::file('photo')->isValid())  {
        $file = \Input::file('photo');
        $ext = $file->getClientOriginalExtension();
        $institution->photo = "/arquigrafia-avatars-inst/".$institution->id.".jpg";
        $institution->save();
        $image = Image::make(\Input::file('photo'))->encode('jpg', 80);
        $image->save(public_path().'/arquigrafia-avatars-inst/'.$institution->id.'.jpg');
        $file->move(public_path().'/arquigrafia-avatars-inst', $institution->id."_original.".strtolower($ext));
      }
      static::updateHeaderInstitution($institution);

      return \Redirect::to("/institutions/{$institution->id}")->with('message', '<strong>Edição de perfil da instituição</strong><br>Dados alterados com sucesso');
    }

  }

  /*formulario para instituição*/
  public function formPhotos() {
    if ( ! Session::has('institutionId') ) {
      return Redirect::to('/home');
    }

    $user_id = Auth::id();
    $exist = false;
    $institution = Institution::find(Session::get('institutionId'));
    $albumsInstitutional = \Album::withInstitution($institution)->get();
    $albums = [ "" => "Escolha o álbum" ];
    foreach ($albumsInstitutional as $album) {
      $albums[$album->id] = $album->title;
    }
    $pageSource = \Request::header('referer');
    $dates = \Input::old('dates');

    return \View::make('form-institutional')->with([
      'pageSource' => $pageSource,
      'user' => Auth::user(), 
      'institution' => $institution,
      'albums' => $albums,
      'dates' => $dates,
      'type' => null,
      'video'=> null
    ]);
  }
   /* Salvar formulario institutional*/
  public function saveFormPhotos() {
    $input = \Input::all();
    if (\Input::has('tagsArea')) {
      $input["tagsArea"] = str_replace(array('\'', '"', '[', ']'), '', $input["tagsArea"]);
    } else {
      $input["tagsArea"] = '';
    }

    if (\Input::has('work_authors')) {
      $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
      $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
    } else {
      $input["work_authors"] = '';
    }

    $regexVideo = '/^(https:\/\/www\.youtube\.com\/(embed\/|watch\?v=)\S+|https:\/\/(player\.)?vimeo\.com\/(video\/)?\S+)$/';
    $rules = array(
      'support' => 'required',
      'tombo' => 'required',
      'hygieneDate' => 'date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
      'backupDate' => 'date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
      'characterization' => 'required',  
      'photo' => 'max:10240|required_without_all:video|mimes:jpeg,jpg,png,gif',
      'photo_name' => 'required',
      'tagsArea' => 'required',
      'type' =>'required',
      'country' => 'required',
      'imageAuthor' => 'required',
      'image_date' => 'date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
      'video' => array('regex:'.$regexVideo,'required_without_all:photo')
    );
    if($input["type"] == "photo") {
        $input["video"] = null;
    }

    $rules = \Input::has('draft') ? array_except($rules, ['photo', 'video']) : $rules;
    $validator = \Validator::make($input, $rules);

    if ($validator->fails()) { 
      $messages = $validator->messages();
      return \Redirect::to('/institutions/form/upload')->withInput($input)->withErrors($messages);
    } else {
      if (\Input::has('draft_id')) {
        $photo = Photo::onlyDrafts()->find(\Input::get('draft_id'));
        $exist = true;
      }

      if ( !isset($photo) ) {
        $photo = new Photo;
      }

      //$photo = new Photo;
      $photo->support = $input["support"];
      $photo->tombo = $input["tombo"];
      $photo->subject = $input["subject"];
      if(!empty($input["hygieneDate"])) {
        $photo->hygieneDate = $this->date->formatDate($input["hygieneDate"]);
      }
      if(!empty($input["backupDate"]) ) {
        $photo->backupDate = $this->date->formatDate($input["backupDate"]);
      }
      $photo->characterization = $input["characterization"];
      $photo->cataloguingTime = date('Y-m-d H:i:s');
      $photo->UserResponsible = $input["userResponsible"];
      $photo->name = $input["photo_name"];
      $photo->type = $input["type"];
      if ( !empty($input["description"]) ) {
        $photo->description = $input["description"];
      }
      if (!empty($input["workDate"])) {
        $photo->workdate = $input["workDate"];
        $photo->workDateType = "year";
      } elseif (!empty($input["decade_select"])) {
        $photo->workdate = $input["decade_select"];
        $photo->workDateType = "decade";
      } elseif (!empty($input["century"]) && $input["century"] != "NS") {
        $photo->workdate = $input["century"];
        $photo->workDateType = "century";
      } else {
        $photo->workdate = NULL;
        $photo->workDateType = NULL;
      }
      if (!empty($input["image_date"])) {
        $photo->dataCriacao = $this->date->formatDate($input["image_date"]);
        $photo->imageDateType = "date";
      } elseif (!empty($input["decade_select_image"])) {
        $photo->dataCriacao = $input["decade_select_image"];
        $photo->imageDateType = "decade";
      } elseif (!empty($input["century_image"]) && $input["century_image"]!="NS") {
        $photo->dataCriacao = $input["century_image"];
        $photo->imageDateType = "century";
      } else {
        $photo->dataCriacao = NULL;
        $photo->imageDateType = NULL;
      }
      $photo->country = $input["country"];
      if ( !empty($input["state"]) ) {
        $photo->state = $input["state"];
      }
      if ( !empty($input["city"]) ) {
        $photo->city = $input["city"];
      }
      if ( !empty($input["street"]) ) {
        $photo->street = $input["street"];
      }
      if ( !empty($input["imageAuthor"]) ) {
        $photo->imageAuthor = $input["imageAuthor"];
      }
      if ( !empty($input["observation"]) ) {
        $photo->observation = $input["observation"];
      }
      if ( !empty($input["aditionalImageComments"]) ) {
        $photo->aditionalImageComments = $input["aditionalImageComments"];
      }
      $photo->allowCommercialUses = $input["allowCommercialUses"];
      $photo->allowModifications = $input["allowModifications"];
      $photo->authorized = $input["authorized"];
      $photo->user_id = Auth::id();
      $photo->dataUpload = date('Y-m-d H:i:s');
      $photo->institution_id = Session::get('institutionId');

      $photo->draft = NULL;

      if (\Input::has('draft')) {
        $photo->nome_arquivo = 'draft';
        $photo->draft();
        $photo->type = NULL;
      } elseif(\Input::hasFile('photo') and \Input::file('photo')->isValid() and $input["type"] == "photo") {
        $file = \Input::file('photo');
        $photo->nome_arquivo = $file->getClientOriginalName();
        $photo->removeDraft();
        $photo->save();
        $ext = $file->getClientOriginalExtension();
        $photo->nome_arquivo = $photo->id . '.' . $ext;
        $photo->type = "photo";
        $photo->video = NULL;

        $angle = array_key_exists('rotate', $input) ? (float) $input['rotate'] : 0;
        $metadata       = Image::make(\Input::file('photo'))->exif();
        $public_image   = Image::make(\Input::file('photo'))->rotate($angle)->encode('jpg', 80);
        $original_image = Image::make(\Input::file('photo'))->rotate($angle);
        $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
        $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg'); 
        $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
        $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));
        $photo->saveMetadata(strtolower($ext), $metadata);
      } elseif (!empty($input["video"])) {
        $videoUrl = $input['video'];
        $array = Photo::getVideoNameAndFile($videoUrl);
        $photo->video = $array['video'];
        $photo->nome_arquivo = $array['file'];
        $photo->type = "video";
      }

      $photo->save();

      if (!empty($input["new_album-name"])) {
        $album = Album::create([
          'title' => $input["new_album-name"],
          'description' => "",
          'user' => Auth::user(),
          'cover' => $photo,
          'institution' => Institution::find(Session::get('institutionId')),
        ]);

        if ($album->isValid()) {
          $photo->albums()->attach($album);
        }
      } elseif (!empty($input["photo_album"])) {
        $photo->albums()->sync([$input["photo_album"]], false);
      }

      $tags = explode(',', $input['tagsArea']);
      if (!empty($tags)) {
        $tags = Tag::formatTags($tags);
        $tagsSaved = Tag::saveTags($tags,$photo);
        if (!$tagsSaved) {
          $photo->forceDelete();
          $messages = ['tagsArea' => ['Inserir pelo menos uma tag']];
          return \Redirect::to('/institutions/form/upload')
            ->withInput($input)->withErrors($messages);
        }
      }

      if (!empty($input["work_authors"])) {
        $author = new Author();
        $author->saveAuthors($input["work_authors"],$photo);
      }

      $input['autoOpenModal'] = 'true';
      $sourcePage = $input["pageSource"]; //get url of the source page through form
      $input['photoId'] = $photo->id;
      $input['dates'] = true;
      $input['dateImage'] = true;
      unset($input['draft_id']);

      return \Redirect::back()->withInput($input);
    }
  }

  /* Edição do formulario institutional*/
  public function editFormPhotos($id) {
    $photo = Photo::find($id);
    $logged_user = Auth::User();
    $institution_id = Session::get('institutionId');
    $work_authors = null;
    if ($logged_user == null || $institution_id == null) {
        return \Redirect::to('/home');
    } elseif ($institution_id == $photo->institution_id) {
        if (Session::has('tagsArea')) {
          $tagsArea = Session::pull('tagsArea');
          $tagsArea = explode(',', $tagsArea);
        } else {
          $tagsArea = $photo->tags->lists('name');
        }

        if (Session::has('work_authors')) {
            $work_authors = Session::pull('work_authors');
            $work_authors = explode(';', $work_authors);
        }else{ 
            $work_authors = $photo->authors->lists('name'); 
        }

      $dateYear = "";
      $decadeInput = "";
      $centuryInput = "";
      $decadeImageInput = "";
      $centuryImageInput = "";
      $imageDateCreated = "";

      if(Session::has('workDate')) {
        $dateYear = Session::pull('workDate');
      } elseif($photo->workDateType == "year") {
        $dateYear = $photo->workdate;
      }

      if(Session::has('imageDate')) {
        $imageDateCreated = Session::pull('imageDate');
      } elseif($photo->imageDateType == "date") {
        $imageDateCreated = $photo->dataCriacao;
      }

      if(Session::has('decadeInput')) {
        $decadeInput = Session::pull('decadeInput');
      } elseif ($photo->workDateType == "decade") {
        $decadeInput = $photo->workdate;
      }

      if(Session::has('centuryInput')){
         $centuryInput = Session::pull('centuryInput');
      }elseif($photo->workDateType == "century") {
         $centuryInput = $photo->workdate;
      }

      if(Session::has('decadeImageInput')) {
         $decadeImageInput = Session::pull('decadeImageInput');
      } elseif ($photo->imageDateType == "decade") {
         $decadeImageInput = $photo->dataCriacao;
      }

      if(Session::has('centuryImageInput')) {
        $centuryImageInput = Session::pull('centuryImageInput');
      } elseif($photo->imageDateType == "century") {
        $centuryImageInput = $photo->dataCriacao;
      }

      if($photo->type == NULL){
          $photo->type = "photo";
      }

      return \View::make('edit-institutional')
        ->with(['photo' => $photo, 'tagsArea' => $tagsArea,
          'dateYear' => $dateYear,
          'centuryInput'=> $centuryInput,
          'decadeInput' =>  $decadeInput,
          'centuryImageInput'=> $centuryImageInput,
          'decadeImageInput' =>  $decadeImageInput,
          'imageDateCreated' => $imageDateCreated,
          'user' => $logged_user,
          'institution' => $photo->institution,
          'work_authors' => $work_authors,
          'type' => $photo->type,
          'video' => $photo->video
        ]);
    }
    return \Redirect::to('/home');
  }

  /* Salvar edição do formulario institutional*/
  public function updateFormPhotos($id) {
    $photo = Photo::find($id);
    \Input::flashExcept('tagsArea','photo','decade_select');
    $input = \Input::all();
    if(\Input::has('tagsArea')){
      $input["tagsArea"] = str_replace(array('\'', '"', '[', ']'), '', $input["tagsArea"]);
    }else{
      $input["tagsArea"] = '';
    }

    if (\Input::has('work_authors')) {
      $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
      $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
    } else
      $input["work_authors"] = '';

    $workDate = "";
    $decadeInput = "";
    $centuryInput = "";
    $imageDateCreated = "";
    $decadeImageInput = "";
    $centuryImageInput = "";

    if(\Input::has('workDate')) {
      $workDate = $input["workDate"];
    } elseif(\Input::has('decade_select')) {
       $decadeInput = $input["decade_select"];
    } elseif(\Input::has('century')) {
       $centuryInput = $input["century"];
    }

    if(\Input::has('photo_imageDate')) {
      $imageDateCreated = $input["photo_imageDate"];
    } elseif(\Input::has('decade_select_image')) {
      $decadeImageInput = $input["decade_select_image"];
    } elseif(\Input::has('century_image')) {
      $centuryImageInput = $input["century_image"];
    }
    $regexVideo = '/^(https:\/\/www\.youtube\.com\/(embed\/|watch\?v=)\S+|https:\/\/(player\.)?vimeo\.com\/(video\/)?\S+)$/';
    $rules = array(
      'support' => 'required',
      'tombo' => 'required',
      'hygieneDate' => 'date_format:"d/m/Y"',
      'backupDate' => 'date_format:"d/m/Y"',
      'characterization' => 'required',
      'type' => 'required',
      'photo_name' => 'required',
      'tagsArea' => 'required',
      'country' => 'required',
      'imageAuthor' => 'required',
      'photo' => 'max:10240|mimes:jpeg,jpg,png,gif',
      'image_date' => 'date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
      'video' => array('regex:'.$regexVideo)
    );
    if($input["type"] == "photo") {
      $input["video"] = null;
    }

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
      $messages = $validator->messages();
      return \Redirect::to('/institutions/'.$photo->id.'/form/edit')->with([
        'tagsArea' => $input['tagsArea'],
        'decadeInput'=>$decadeInput,
        'centuryInput'=>$centuryInput,
        'workDate' => $workDate,
        'decadeImageInput' => $decadeImageInput,
        'centuryImageInput' => $centuryImageInput,
        'imageDateCreated' => $imageDateCreated,
        'work_authors'=> $input["work_authors"],
        'type'=> $input["type"],
        'video' => $input["video"]
      ])->withErrors($messages);
    } else {
      if(!empty($input["aditionalImageComments"]) )
        $photo->aditionalImageComments = $input["aditionalImageComments"];
      $photo->support = $input["support"];
      $photo->tombo = $input["tombo"];
      $photo->subject = $input["subject"];
      if ( !empty($input["hygieneDate"]) )
        $photo->hygieneDate = $this->date->formatDate($input["hygieneDate"]);
      else $photo->hygieneDate = null;

      if ( !empty($input["backupDate"]) )
        $photo->backupDate = $this->date->formatDate($input["backupDate"]);
      else $photo->backupDate = null;
      $photo->characterization = $input["characterization"];
      $photo->cataloguingTime = date('Y-m-d H:i:s');
      $photo->UserResponsible = $input["userResponsible"];
      $photo->name = $input["photo_name"];

      if (!empty($input["description"]))
        $photo->description = $input["description"];
      else $photo->description = null;

      if(!empty($input["workDate"])) {
        $photo->workdate = $input["workDate"];
        $photo->workDateType = "year";
      } elseif(!empty($input["decade_select"])) {
        $photo->workdate = $input["decade_select"];
        $photo->workDateType = "decade";
      } elseif (!empty($input["century"]) && $input["century"]!="NS") {
        $photo->workdate = $input["century"];
        $photo->workDateType = "century";
      } else {
        $photo->workdate = NULL;
        $photo->workDateType = NULL;
      }

      if(!empty($input["image_date"])) {
        $photo->dataCriacao = $this->date->formatDate($input["image_date"]);
        $photo->imageDateType = "date";
      } elseif(!empty($input["decade_select_image"])) {
        $photo->dataCriacao = $input["decade_select_image"];
        $photo->imageDateType = "decade";
      } elseif (!empty($input["century_image"]) && $input["century_image"]!="NS") {
        $photo->dataCriacao = $input["century_image"];
        $photo->imageDateType = "century";
      } else {
        $photo->dataCriacao = NULL;
      }

      $photo->country = $input["country"];
      $photo->type = $input["type"];
      if (!empty($input["state"]))
        $photo->state = $input["state"];
      else $photo->state = null;

      if ( !empty($input["city"]) )
        $photo->city = $input["city"];
      else $photo->city = null;
      if ( !empty($input["street"]) )
        $photo->street = $input["street"];
      else $photo->street = null;

      if ( !empty($input["imageAuthor"]) )
        $photo->imageAuthor = $input["imageAuthor"];

      if ( !empty($input["observation"]) )
        $photo->observation = $input["observation"];
      else $photo->observation = null;

      $photo->allowCommercialUses = $input["allowCommercialUses"];
      $photo->allowModifications = $input["allowModifications"];
      $photo->user_id = Auth::user()->id;
      $photo->dataUpload = date('Y-m-d H:i:s');
      $photo->institution_id = Session::get('institutionId');

      if ($input["type"] == "video") {
        $videoUrl = $input['video'];
        $array = Photo::getVideoNameAndFile($videoUrl);
        $photo->video = $array['video'];
        $photo->nome_arquivo = $array['file'];
        $photo->type = "video";
      } else {
        if(\Input::hasFile('photo') and \Input::file('photo')->isValid() and $input["type"] == "photo") {
          $file = \Input::file('photo');
          $ext = $file->getClientOriginalExtension();
          $photo->nome_arquivo = $photo->id.".".$ext;
          $photo->type = "photo";
          $photo->video = NULL;
        }
      }

      $photo->touch();
      $photo->save();

      //tags
      $tagsCopy = $input['tagsArea'];
      $tags = explode(',', $input['tagsArea']);

      if(!empty($tags)) {
        $tags = Tag::formatTags($tags);
        $tagsSaved = Tag::updateTags($tags,$photo);

        if(!$tagsSaved){
          $photo->forceDelete();
          $messages = array('tagsArea'=>array('Inserir pelo menos uma tag') );

          return \Redirect::to('/institutions/'.$photo->id.'/form/edit')->with([
          'tagsArea' => $input['tagsArea'] ])->withErrors($messages);
        }
      }

      $author = new Author();
      if (!empty($input["work_authors"])) {
        $author->updateAuthors($input["work_authors"],$photo);
      } else {
        $author->deleteAuthorPhoto($photo);
      }
      $create = false;
      if (\Input::hasFile('photo') and \Input::file('photo')->isValid() and $input["type"] == "photo") {
        if(array_key_exists('rotate', $input))
          $angle = (float)$input['rotate'];
        else
          $angle = 0;
        $metadata       = Image::make(\Input::file('photo'))->exif();
        $public_image   = Image::make(\Input::file('photo'))->rotate($angle)->encode('jpg', 80);
        $original_image = Image::make(\Input::file('photo'))->rotate($angle);
        $create = true;
      } elseif ($input["type"] == "photo") {
        list($photo_id, $ext) = explode(".", $photo->nome_arquivo);
        $path                 = storage_path().'/original-images/'.$photo->id.'_original.'.$ext;
        $metadata             = Image::make($path)->exif();

        if (array_key_exists('rotate', $input) and ($input['rotate'] != 0)) {
          $angle = (float)$input['rotate'];
          $public_image   = Image::make($path)->rotate($angle)->encode('jpg', 80);
          $original_image = Image::make($path)->rotate($angle);
          $create = true;
        } else
          $create = false;
      }

      if ($create) {
        $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
        $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg');
        $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
        $public_image->fit(32,20)->save(public_path().'/arquigrafia-images/'.$photo->id.'_micro.jpg');
        $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));
        $photo->saveMetadata(strtolower($ext), $metadata);
      }

      return \Redirect::to("/photos/".$photo->id)->with('message',
        '<strong>Edição de informações da imagem</strong><br>Dados alterados com sucesso');
    }
  }

  public static function updateHeaderInstitution($institution) {
    if($institution != null) {
      $displayedInstitutionName = HelpTool::formattingLongText($institution->name, $institution->acronym, 25);
      Session::put('displayInstitution', $displayedInstitutionName);
    }
  }


  public function followInstitution($institution_id) {
    $logged_user = Auth::user();
    if ($logged_user == null)  return \Redirect::to('/home');

    $following = $logged_user->followingInstitution;
    if (!$following->contains($institution_id)) {
      $logged_user->followingInstitution()->attach($institution_id);
    }
    return \Redirect::to(\URL::previous());
  }

  public function unfollowInstitution($institution_id) {
    $logged_user = Auth::user();
    if ($logged_user == null)   return \Redirect::to('/home');

    $following = $logged_user->followingInstitution;

    if ($following->contains($institution_id))
      $logged_user->followingInstitution()->detach($institution_id);
    return \Redirect::to(\URL::previous());
  }

  public function sendNotification($id) {
    $logged_user = Auth::user();
    if ($id != $logged_user->id) {
      $institution_notified = Institution::find($id);
      foreach ($institution_notified->notifications as $notification) {
        $info = $notification->render();
        if ($info[0] == "follow" && $notification->read_at == null) {
          $note_id = $notification->notification_id;
          $note_user_id = $notification->id;
          $note = $notification;
        }
      }
      if (isset($note_id)) {
        $note_from_table = \DB::table("notifications")->where("id","=", $note_id)->get();
        if (Notification::isNotificationByUser($logged_user->id, $note_from_table[0]->sender_id, $note_from_table[0]->data) == false) {
          $new_data = $note_from_table[0]->data . ":" . $logged_user->id;
          DB::table("notifications")->where("id", "=", $note_id)->update(array("data" => $new_data, "created_at" => Carbon::now('America/Sao_Paulo')));
          $note->created_at = Carbon::now('America/Sao_Paulo');
          $note->save();
        }
      }
      else Notification::create('follow', $logged_user, $user_note, [$user_note], null);
    }
  }

  public function destroy($id) {
    $photo = Photo::find($id);
    foreach($photo->tags as $tag) {
      $tag->count = $tag->count - 1;
      $tag->save();
    }
    \DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();

    $photo->delete();
    return \Redirect::to('/institutions/' . $photo->institution_id);
  }

  public function allImages($id) {
    $institution = Institution::find($id);
    $photos = Institution::paginatePhotosInstitution($id,$institution);
    return \View::make('images-institution')->with(array('photos'=>$photos,'institution' => $institution ));
  }

  public function paginatePhotosInstitution() {
    if(Session::has('last_paginate')) {
      Log::info("Passou");
      $lastPaginate = Session::get('last_paginate');
      $photos = $lastPaginate['photos'];
    }
    $institution = Input::has('inst') ? Input::get('inst') : '';
    $pagination = Institution::paginatePhotosInstitutionAll($photos);

    return $this->paginationResponseSearch($pagination, 'add');
  }

  public function employment_management() {
    $user = \Auth::user();
    if ($user->admin == False) {
      return \Redirect::to('/home');
    }
    $users = User::all();
    $employments = Employee::all();
    $institutions = Institution::all();
    $roles = Role::all();
    $employees = [];
    $names = [];
    foreach($institutions as $i) {
      $names[$i->id] = $i->name;
    }
    foreach($employments as $e) {
      $user = $user->find($e->user_id);
      $inst = $institutions->find($e->institution_id);
      $role = $roles->find($e->role_id);
      $employees[$e->id] = [];
      $employees[$e->id]['id'] = $e->id;
      $employees[$e->id]['user'] = $user->login;
      $employees[$e->id]['institution'] = $inst->name;
      if ($role != NULL) {
        $employees[$e->id]['role'] = $role->name;
      }
      else {
        $employees[$e->id]['role'] = "Indefinido";
      }
    }
    
    return \View::make('/employment_management',
    ['employees' => $employees, 'institutions' => $institutions, 'names' => $names]);
  }

  public function create_employment() {
    $user = \Auth::user();
    if ($user->admin == False) 
    {
        return \Redirect::to('/home');
    }
    $institutions = Institution::all();
    $roles = Role::all();
    $inst_names = [];
    $role_names = [];
    foreach($institutions as $i) { 
      $inst_names[$i->id] = $i->name;
    }
    $role_names[0] = "Indefinido";
    foreach($roles as $r) { 
      $role_names[$r->id] = $r->name;
    }

    return \View::make('create_employment',
    ['inst_names' => $inst_names, 'role_names' => $role_names]);
  }

  public function store_employment()
  {
      // first, we will receive the form from the tag.create view
      $input = \Input::all();

      $validator = \Validator::make(
          $input,
          array(
              'name' => 'required|exists:users,login',
          )
      );
      $user = User::where('login', $input['name'])->first();
      $existing = Employee::where('user_id', $user->id)->where('institution_id', $input['institution'])->get();
      if ($validator->fails() or count($existing) > 0)
      {
        return \Redirect::to('/employment-management/create-employment');
      }

      $employment = new Employee;
      $employment->user_id = $user->id;
      $employment->institution_id = $input['institution'];
      if ($input['role'] != 0 and $input['role'] != NULL) {
        $employment->role_id = $input['role'];
      }
      
      $employment->save();
      return \Redirect::to('/employment-management');
  }

  public function destroy_employment($id){
    $user = \Auth::user();
    if ($user->admin == False) 
    {
      return \Redirect::to('/home');
    }
    Employee::find($id)->delete();
    return \Redirect::to('/employment-management');
  }

  private function paginationResponseSearch($photos, $type) {
    Log::info("paginateRsp");
    $count = $photos->getTotal();
    $perPage = $photos->getPerPage();
    $page = $photos->getCurrentPage();
    Log::info("count====".$count);

    $fromPage = $photos->getFrom();
    $toPage = $photos->getTo();

    Log::info("Response>> CurrPage=".$page." FromPage=".$fromPage." ToPage=".$toPage." count=".$count." perPage=".$perPage);

    Session::put('FromPage', $fromPage);
    Session::put('ToPage', $toPage);
    Session::put('CurrPage', $page);

    $response = [];
    $response['content'] = \View::make('includes.result_images')
      ->with(['photos' => $photos, 'page' => $page, 'type' => $type])
      ->render();
    $response['maxPage'] = $photos->getLastPage();
    $response['empty'] = ($photos->count() == 0);
    $response['count'] = $count;

    return Response::json($response);
  }
}
