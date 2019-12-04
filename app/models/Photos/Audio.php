<?php

class Audio extends Eloquent {

  protected $table = 'audios';

  protected $fillable = ['file', 'name', 'description', 'user_id', 'photo_id'];

  public function photo() {
    return $this->belongsTo('Photo');
  }

  public function user() {
    return $this->belongsTo('User');
  }

}
