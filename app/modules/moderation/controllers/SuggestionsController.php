<?php

namespace modules\moderation\controllers;

use lib\log\EventLogger;
use Carbon\Carbon;
use Photo;
use modules\moderation\models\Suggestion;
use modules\moderation\models\PhotoAttributeType;

class SuggestionsController extends \BaseController {


	public function testname(){
		$input = \Input::all();

		$rules = array(
			'user_id' => 'required',
			'photo_id' =>'required',
			'attribute_type' => 'required',
			'text' => 'required'
		);

		$validator = \Validator::make($input, $rules);
		if($validator->fails()){
			return \Response::make($validator->messages(), 400);
		}

		$suggestion = new Suggestion();
		//user_id
		$suggestion->user_id = $input['user_id'];
		//photo_id
		$suggestion->photo_id = $input['photo_id'];
		//attribute_type
		$attribute = PhotoAttributeType::find($input['attribute_type']);
		$suggestion->attribute_type = $attribute->id;
		//text
		$suggestion->text = $input['text'];
		//moderator_id
		//TO DO
		//accepted
		$suggestion->accepted = false;
		$suggestion->save();
		EventLogger::printEventLogs(null, 'completion', ['suggestion' => $suggestion->id], 'Web');
		return \Response::json('Data sent successfully');
	}
}