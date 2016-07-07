<?php 
namespace lib\api\controllers;

class APIFeedController extends \BaseController {

	public function loadFeed($id)
	{
		$user = \User::find($id);
		$following_users = $user->following;
		$following_institutions = $user->followingInstitution;
		$users_ids = $following_users->lists('id');
		$institutions_ids = $following_institutions->lists('id');
		$users_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->whereIn('user_id', $users_ids);
		$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->whereIn('institution_id', $institutions_ids);
		$all_photos = $institutions_photos->union($users_photos)->orderBy('created_at', 'desc')->take(20)->get();
		$result = [];
		foreach ($all_photos as $photo) {
			if(is_null($photo->institution_id)) {
				array_push($result, ["photo" => $photo, "sender" => \User::find($photo->user_id)]);
			}
			else {
				array_push($result, ["photo" => $photo, "sender" => \Institution::find($photo->institution_id)]);	
			}
		}
		return \Response::json($result);
	}

	public function loadMoreFeed($id) {
		$input = \Input::all();
		$max_id = $input["max_id"];

		$user = \User::find($id);
		$following_users = $user->following;
		$following_institutions = $user->followingInstitution;
		$users_ids = $following_users->lists('id');
		$institutions_ids = $following_institutions->lists('id');
		$users_photos = \DB::table('photos')->whereNull('deleted_at')->whereNull('institution_id')->where('id', '<', $max_id)->whereIn('user_id', $users_ids);
		$institutions_photos = \DB::table('photos')->whereNull('deleted_at')->where('id', '<', $max_id)->whereIn('institution_id', $institutions_ids);
		$all_photos = $institutions_photos->union($users_photos)->orderBy('created_at', 'desc')->take(20)->get();
		$result = [];
		foreach ($all_photos as $photo) {
			if(is_null($photo->institution_id)) {
				array_push($result, ["photo" => $photo, "sender" => \User::find($photo->user_id)]);
			}
			else {
				array_push($result, ["photo" => $photo, "sender" => \Institution::find($photo->institution_id)]);	
			}
		}
		return \Response::json($result);
	}
}