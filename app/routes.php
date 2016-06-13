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

/* DRAFTS */
Route::post('/drafts/delete', 'PhotosController@deleteDraft');
Route::get('/drafts/paginate', 'PhotosController@paginateDrafts');
Route::get('/drafts/{id}', 'PhotosController@getDraft');
Route::get('/drafts', 'PhotosController@listDrafts');
/* IMPORTS */
Route::get('/photos/import', 'ImportsController@import');

/* phpinfo() */
Route::get('/info/', function(){ return View::make('i'); });

Route::get('/', 'PagesController@home');
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
Route::get('/friends/followInstitution/{institution_id}', 'InstitutionsController@followInstitution');
Route::get('/friends/unfollowInstitution/{institution_id}', 'InstitutionsController@unfollowInstitution');

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


/* COMMENTS */
Route::post('/photos/{photo_id}/comment','PhotosController@comment');
Route::get('/comments/{comment_id}/like','lib\gamification\controllers\LikesController@commentlike');
Route::get('/comments/{comment_id}/dislike','lib\gamification\controllers\LikesController@commentdislike');

/* EVALUATIONS */
Route::get('/photos/{photo_id}/saveEvaluation','PhotosController@saveEvaluation');
Route::post('/photos/{photo_id}/saveEvaluation','PhotosController@saveEvaluation');
Route::get('/photos/{photo_id}/evaluate','PhotosController@evaluate');
Route::post('/photos/{photo_id}/evaluate','PhotosController@evaluate');
Route::get('/photos/{photo_id}/viewEvaluation/{user_id}','PhotosController@viewEvaluation');
Route::get('/photos/{photo_id}/showSimilarAverage/', 'PhotosController@showSimilarAverage');

/* PHOTOS */
Route::get('/photos/{id}/like', 'lib\gamification\controllers\LikesController@photolike');
Route::get('/photos/{id}/dislike', 'lib\gamification\controllers\LikesController@photodislike');
Route::resource('/groups','GroupsController');
Route::get('/photos/batch','PhotosController@batch');
Route::get('/photos/upload','PhotosController@form');
Route::get('/photos/uploadInstitutional','PhotosController@formInstitutional');
Route::get('/photos/{photo_id}/editInstitutional','PhotosController@editFormInstitutional');
Route::put('/photos/{photo_id}/update/Institutional','PhotosController@updateInstitutional');
Route::get('/photos/migrar','PhotosController@migrar');
Route::get('/photos/rollmigrar','PhotosController@rollmigrar');
//Route::get('/photos/{photo_id}/update/Institutional','PhotosController@updateInstitutional');

Route::get('/photos/download/{photo_id}','PhotosController@download');
Route::get('/photos/savePhotoInstitutional','PhotosController@saveFormInstitutional');
Route::post('/photos/savePhotoInstitutional','PhotosController@saveFormInstitutional');
Route::resource('/photos','PhotosController');

/* TAGS */
Route::get('/tags/json', 'TagsController@index');
Route::get('/tags/refreshCount', 'TagsController@refreshCount');

/* NOTIFICATIONS */
Route::get('/notifications', 'NotificationsController@show');
Route::get('/markRead/{id}', 'NotificationsController@read');
Route::get('/readAll', 'NotificationsController@readAll');

Route::get('/refreshBubble', 'NotificationsController@howManyUnread');

/* INSTITUTIONS */
Route::get('/institutions/{id}', 'InstitutionsController@show');
Route::get('/institutions/{id}/edit', 'InstitutionsController@edit');
Route::resource('/institutions','InstitutionsController');

/* SEARCH PAGE */
Route::get('/search/paginate/other/photos', 'PagesController@paginatePhotosResult');
Route::get('/search/more/paginate/other/photos', 'PagesController@paginatePhotosResultAdvance');

/* REST API */
Route::group(array('prefix' => 'api/'), function()
{
    Route::resource('photos'   , 'lib\api\controllers\APIPhotosController');
    Route::resource('users'    , 'lib\api\controllers\APIUsersController');
    Route::post(    'login'    , 'lib\api\controllers\APILogInController@verify_credentials');
    Route::post(	'auth'     , 'lib\api\controllers\APILogInController@validate_mobile_token');
    Route::post(	'logout'   , 'lib\api\controllers\APILogInController@log_out');
    Route::get(		'feed/{id}', 'lib\api\controllers\APIFeedController@show');
    Route::get(	'loadMore/{id}', 'lib\api\controllers\APIFeedController@loadMore');
});
