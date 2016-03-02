<?php

/* GAMIFICATION */
Route::get('/photos/{id}/get/field', 'lib\gamification\controllers\QuestionsController@getField');
Route::post('/photos/{id}/set/field', 'lib\gamification\controllers\QuestionsController@setField');
Route::get('/rank/get', 'lib\gamification\controllers\ScoresController@getRankEval');
Route::get('/leaderboard', 'lib\gamification\controllers\ScoresController@getLeaderboard');
Route::get('/badges/{id}', 'lib\gamification\controllers\BadgesController@show');
Route::get('/quiz', 'lib\gamification\controllers\QuizController@index');