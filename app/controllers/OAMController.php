<?php

class OAMController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return View::make('oam.index');
	}

	public function place()
	{
		//
		$street = Input::get('street');
		$photos = Photo::where('street', 'LIKE', $street . '%')->orderBy('id', 'desc')->limit(50)->get();

		$ids = array_pluck($photos, 'id');
		$audios = Audio::whereIn('photo_id', $ids)->with(['user','photo'])->orderBy('id', 'desc')->get();

		return View::make('oam.place', [
			'place' => $street,
			'photos' => $photos,
			'audios' => $audios,
		]);
	}

	public function photo($id)
	{
		//
		if (Auth::check()) {
			$user = Auth::user();
		} else {
			return Redirect::to('/home');
		}

		$photo = Photo::find($id);
		$audios = Audio::where('photo_id', $id)->with(['user','photo'])->orderBy('id', 'desc')->get();

		return View::make('oam.photo', [
			'photo' => $photo,
			'user' => $user,
			'audios' => $audios,
		]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function storeAudio() {

		$name = time() . '.mp3';

		$upload = Input::file('audio');
		$upload->move(public_path('audios'), $name);

		$audio = new Audio();
		$audio->file = $name;
		$audio->user_id = Input::get('user');
		$audio->photo_id = Input::get('photo');
		if ($audio->save()) {
			return Response::json(['status'=>'success']);
		}
		return Response::json($audio->getErrors());
	}


}
