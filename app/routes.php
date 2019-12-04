<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/test', function () {
  //testes
});

Route::get('/photos/import', 'ImportsController@import');

/* phpinfo() */
Route::get('/info/', function(){ return View::make('i'); });

Route::group(['prefix' => '/'], function()
{
    if ( Auth::check() ) // use Auth::check instead of Auth::user
    {
        Route::get('/', 'PagesController@home');
    } else{
        Route::get('/', 'PagesController@main');
    }
});



Route::get('/landing/{language?}', 'PagesController@landing');
Route::get('/home', 'PagesController@home');
Route::get('/panel', 'PagesController@panel');
Route::get('/project', function() { return View::make('project'); });
Route::get('/faq', function() { return View::make('faq'); });
Route::get('/chancela', function() { return View::make('chancela'); });
Route::get('/termos', function() { return View::make('termos'); });

/* SEARCH */
Route::get('/search', 'PagesController@search');
Route::post('/search', 'PagesController@search');
Route::get('/search/more', 'PagesController@advancedSearch');

/* USERS */
Route::get('/users/account', 'UsersController@account');
Route::get('/users/register/', 'UsersController@emailRegister');
Route::get('/users/verify/{verifyCode}','UsersController@verify');
Route::get('/users/verify/','UsersController@verifyError');
Route::get('/users/login', 'UsersController@loginForm');
Route::post('/users/login', 'UsersController@login');
Route::get('/users/logout', 'UsersController@logout');
Route::get('users/login/fb', 'UsersController@facebook');
Route::get('users/login/fb/callback', 'UsersController@callback');
Route::get('/users/forget', 'UsersController@forgetForm');
Route::post('/users/forget', 'UsersController@forget');
Route::get('/getPicture', 'UsersController@getFacebookPicture');

Route::resource('/users','UsersController');
Route::resource('/users/stoaLogin','UsersController@stoaLogin');

Route::resource('/users/institutionalLogin','UsersController@institutionalLogin');

/* FOLLOW */
Route::get('/friends/follow/{user_id}', 'UsersController@follow');
Route::get('/friends/unfollow/{user_id}', 'UsersController@unfollow');

// AVATAR
Route::get('/profile/10/showphotoprofile/{profile_id}', 'UsersController@profile');

/* ALBUMS */
Route::resource('/albums','AlbumsController');
Route::get('/albums/photos/add', 'AlbumsController@paginateByUser');
Route::get('/albums/{id}/photos/rm', 'AlbumsController@paginateByAlbum');
Route::get('/albums/{id}/photos/add', 'AlbumsController@paginateByOtherPhotos');
Route::get('/albums/get/list/{id}', 'AlbumsController@getList');
Route::post('/albums/photo/add', 'AlbumsController@addPhotoToAlbums');
Route::delete('/albums/{album_id}/photos/{photo_id}/remove', 'AlbumsController@removePhotoFromAlbum');

/* ALBUMS - ajax */
Route::get('/albums/get/cover/{id}', 'AlbumsController@paginateCoverPhotos');
Route::post('/albums/{id}/update/info', 'AlbumsController@updateInfo');
Route::post('/albums/{id}/detach/photos', 'AlbumsController@detachPhotos');
Route::post('/albums/{id}/attach/photos', 'AlbumsController@attachPhotos');
Route::get('/albums/{id}/paginate/photos', 'AlbumsController@paginateAlbumPhotos');
Route::get('/albums/{id}/paginate/other/photos', 'AlbumsController@paginatePhotosNotInAlbum');

Route::get('/photos/batch','PhotosController@batch');
Route::get('/photos/upload','PhotosController@form');
Route::get('/photos/migrar','PhotosController@migrar');
Route::get('/photos/rollmigrar','PhotosController@rollmigrar');
Route::get('/photos/download/{photo_id}','PhotosController@download');
Route::get('/photos/completeness', 'PhotosController@showCompleteness');
Route::get('/photos/to_complete', 'PhotosController@getCompletenessPhotos');
Route::resource('/photos','PhotosController');

/* SEARCH PAGE */
Route::get('/search/paginate/other/photos', 'PagesController@paginatePhotosResult');
Route::get('/search/more/paginate/other/photos', 'PagesController@paginatePhotosResultAdvance');

/* OAM */
Route::get('oam', 'OAMController@index');
Route::get('oam/place', 'OAMController@place');
Route::get('oam/photo/{id}', 'OAMController@photo');

/* AUDIO */
Route::post('oam/audios', 'OAMController@storeAudio');

/* TOMBOS */
Route::get('/tombos', 'PhotosController@filterTombos');
Route::post('/tombos', 'PhotosController@filterTombos');

/* RELATÓRIOS */
Route::get('/adm-reports', 'PagesController@admin_reports');
Route::post('/adm-reports', 'PagesController@admin_reports');