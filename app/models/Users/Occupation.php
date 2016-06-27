<?php

class Occupation extends Eloquent {

	public $timestamps = false;

	protected $fillable = ['id', 'institution', 'occupation', 'user_id'];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public static function userOccupation($user_id){
		//$occupations = User::where('user_id', '=', $user_id)->toArray();
		if($user_id != null){
			$arrayOccupations = array();
			$evaluations = DB::table('occupations')
			->select('occupation')
			->where('user_id', $user_id)->get();
			
			foreach ($evaluations as $valOccupations) {
				 
				 $arrayOccupations[] = $valOccupations; //->occupation;
			}
			 return $arrayOccupations;			
		}	

	}

	public static function updateUserIdInOccupation($accountFrom, $accountTo){
      //DB::table('occupations')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
      Occupation::where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
  	}  
}