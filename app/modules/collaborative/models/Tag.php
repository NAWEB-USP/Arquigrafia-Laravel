<?php
namespace modules\collaborative\models;
use modules\institutions\models\Institution;
use Photo;
use Session;
class Tag extends \Eloquent {

  public $timestamps = false;

  protected $fillable = ['name'];

  public function tag() {
    return $this->belongsTo('App/Tag');
  }

  public function tags() {
    return $this->hasMany('App/Tag');
  }
  
  public function photos() {
    return $this->belongsToMany('Photo', 'tag_assignments', 'tag_id', 'photo_id');
  }

  public function photosTags($tags){
    $query = Tag::whereIn('name', $tags);
    $tagsResult = $query->get();
    $listTags= $tagsResult->lists('id');
    $photosTagAssignment = \DB::table('tag_assignments')
      ->select('photo_id')
      ->whereIn('tag_id',$listTags)
      ->get();

    $listPhotos=$photosTagAssignment->lists('photo_id');
    $photos = Photo::wherein('id',$listPhotos);

    return $photos;
  }

  public static function allTagsPhoto($photo_id){     
    $photosTagAssignment = \DB::table('tag_assignments')
      ->select('tag_id')
      ->where('photo_id',$photo_id)
      ->lists('tag_id');

    $listTags = array();
    
     $tags = Tag::wherein('id',$photosTagAssignment)->get(); 
     
     return $tags;
  }

  public static function allTagsPhotoByType($photo_id,$type){     

    $photosTagType = \DB::table('tag_assignments')
            ->join('tags', 'tag_assignments.tag_id', '=', 'tags.id')
            ->select('tags.id')
            ->where('tag_assignments.photo_id',$photo_id)
            ->where('tags.type',$type)
            ->lists('tags.id');


     $tags = Tag::wherein('id',$photosTagType)->get(); 
     
     return $tags;
  }

  public static function getOrCreate($name, $type = null) {
    $tag = static::firstOrNew(['name' => $name]);
    $tag->type = $type;
    $tag->incrementReferences();
    $tag->save();
    return $tag;
  }

  public static function transform($raw_tags) {
    $tags = explode(',', $raw_tags);
    $tags = array_map('trim', $tags);
    $tags = array_map('mb_strtolower', $tags);
    $tags = array_filter($tags);
    return array_unique($tags);
  }

  public function incrementReferences() {
    $this->count = $this->count == null ? 1 : $this->count + 1;
  }

  /*usado por institutions*/
  public static function formatTags($tagsType){
    $tagsType = array_map('trim', $tagsType);
    $tagsType = array_map('mb_strtolower', $tagsType); 
    $tagsType = array_unique($tagsType);    
    return $tagsType;
  }

  public static function saveTags($tags,$photo)
  {
    try {
      $saved_tags = [];
      foreach ($tags as $t) {
        $tag = Tag::where('name', $t)
         ->whereIn('type', array('Acervo','Livre'))->first();
        if ( is_null($tag) ) {
          $tag = new Tag();
          $tag->name = $t;
          $tag->type = 'Livre';
        }
        if($tag->count == null) $tag->count = 0;
        $tag->count++;
        $tag->save();
        $saved_tags[] = $tag->id;
      }
      $photo->tags()->sync($saved_tags, false);
      $saved = true;

    } catch (PDOException $e) {
      Log::error("Logging exception, error to register tags");           
      $saved = false;  
    } 
    return $saved;
  }

  public static function updateTags($newTags,$photo){
  
      $photo_tags = $photo->tags;
      $allTags = Tag::allTagsPhoto($photo->id); 
      foreach ($allTags as $tag){   
        $tag->count--;
        $tag->save();                
      }

      foreach ($allTags as $alltag) {
        $photo->tags()->detach($alltag->id);
      }
      try{
        foreach ($newTags as $t) {            
            $t = strtolower($t);           
             
            $tag = Tag::where('name', $t)
                     ->whereIn('type', array('Acervo','Livre'))->first();
    
            if(is_null($tag)){
                $tag = new Tag();
                $tag->name = $t;
                $tag->type = 'Livre';
                $tag->save();
            }  
            $photo->tags()->attach($tag->id);

            if($tag->count == null)
                $tag->count = 0;
            $tag->count++;
            $tag->save(); 
        }
        $saved = true;

      }catch(PDOException $e){
          Log::error("Logging exception, error to register tags");           
          $saved = false;
      }
      return $saved;  
  }

  public static function filterTagByType($photo,$tagType){
      $tagsArea = $photo->tags->toJson();
      $jsonTagsArea=json_decode($tagsArea);      
      $arrayTags = array_filter($jsonTagsArea,function($item) use ($tagType){
        return $item->type == $tagType;
      });
      $tagsTypeList = array(); 
      foreach ($arrayTags as $value) {
        array_push($tagsTypeList, $value->name);
      }
      return $tagsTypeList;
  } 
}