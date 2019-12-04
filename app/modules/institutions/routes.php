<?php

/* INSTITUTIONS */
Route::get('/institution-management', 'modules\institutions\controllers\InstitutionsController@index');
Route::get('/institution-management/create', 'modules\institutions\controllers\InstitutionsController@create');
Route::post('/institution-management', 'modules\institutions\controllers\InstitutionsController@store');
Route::delete('/institution-management/{id}', 'modules\institutions\controllers\InstitutionsController@destroy_institution');
Route::get('/institutions/{id}', 'modules\institutions\controllers\InstitutionsController@show');
Route::get('/institutions/{id}/edit', 'modules\institutions\controllers\InstitutionsController@edit');
Route::get('/institutions/form/upload','modules\institutions\controllers\InstitutionsController@formPhotos');
Route::post('/institutions/save','modules\institutions\controllers\InstitutionsController@saveFormPhotos');
Route::get('/institutions/{photo_id}/form/edit','modules\institutions\controllers\InstitutionsController@editFormPhotos');
Route::put('/institutions/{photo_id}/update/photo','modules\institutions\controllers\InstitutionsController@updateFormPhotos');

Route::get('/institutions/{id}/allphotos', 'modules\institutions\controllers\InstitutionsController@allImages');

/* FOLLOW */
Route::get('/friends/followInstitution/{institution_id}', 'modules\institutions\controllers\InstitutionsController@followInstitution');
Route::get('/friends/unfollowInstitution/{institution_id}', 'modules\institutions\controllers\InstitutionsController@unfollowInstitution');

Route::resource('/institutions','modules\institutions\controllers\InstitutionsController');

/* GERENCIAMENTO DE CARGOS */
Route::get('/employment-management', 'modules\institutions\controllers\InstitutionsController@employment_management');
Route::get('/employment-management/create-employment', 'modules\institutions\controllers\InstitutionsController@create_employment');
Route::delete('/employment-management/{id}', 'modules\institutions\controllers\InstitutionsController@destroy_employment');
Route::post('/employment-management', 'modules\institutions\controllers\InstitutionsController@store_employment');


