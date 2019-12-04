<?php
/* TAGS */
Route::get('/tags/json', 'modules\collaborative\controllers\TagsController@indexAll');
Route::get('/tags/refreshCount', 'modules\collaborative\controllers\TagsController@refreshCount');

/* COMMENTARIOS */
Route::post('/comments/{photo_id}','modules\collaborative\controllers\CommentsController@comment');
Route::get('/comments/{comment_id}/like','modules\collaborative\controllers\CommentsController@commentLike');
Route::get('/comments/{comment_id}/dislike','modules\collaborative\controllers\CommentsController@commentDislike');
Route::resource('/comments','modules\collaborative\controllers\CommentsController');

/* LIKE E DISLIKE */
Route::get('/like/{id}', 'modules\collaborative\controllers\LikesController@photoLike');
Route::get('/dislike/{id}', 'modules\collaborative\controllers\LikesController@photoDislike');
Route::resource('/likes','modules\collaborative\controllers\LikesController');

/* GRUPOS */
Route::resource('/groups','modules\collaborative\controllers\GroupsController');

/*EVENTS */
Event::subscribe('modules\collaborative\subscriber\LikeSubscriber');
/*REPORTs*/
Route::post('/reports/photo','modules\collaborative\controllers\ReportsController@reportPhoto');
Route::get('/reports/showModalReport/{id}', 'modules\collaborative\controllers\ReportsController@showModalReportPhoto');
//Route::get('/photos/showModalReport/{id}', 'ReportController@showModalReportPhoto');

Route::get('/tags','modules\collaborative\controllers\TagsController@index');
Route::get('/tags/create','modules\collaborative\controllers\TagsController@create');
Route::get('/tags/{tag_id}/edit','modules\collaborative\controllers\TagsController@edit');
Route::resource('/tags','modules\collaborative\controllers\TagsController');