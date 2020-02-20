<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::get('list','Users@list');

Route::post('login', 'API\APIController@login');
Route::post('register', 'API\APIController@register');
Route::get('logout', 'API\APIController@logout')->name('logout');

Route::group(['middleware' => ['auth:api'], 'namespace' => 'API'], function(){
   
    // Route::get('/users', ['as'=>'users.index', 'uses'=>'APIController@index']);

    // Route::post('/users', ['as'=>'users.store', 'uses'=>'APIController@store']);

    // Route::put('/users/{user}', ['as'=>'users.update', 'uses'=>'APIController@update']);
    
    // Route::delete('/users/{user}', ['as'=>'users.delete', 'uses'=>'APIController@destroy']);
    
    // Route::get('/users/{user}', ['as'=>'users.show', 'uses'=>'APIController@show']);
    Route::ApiResource('users', 'APIController');
    Route::post('/users/import', ['as'=>'users.import', 'uses'=>'APIController@import']);

});

// Route::post('/v1/notes', 'API\NotesController@create');
// Route::get('/v1/notes', 'API\NotesController@allNotes');
// Route::delete('v1/notes/{id}', 'API\NotesController@permanentDelete'); //this on first permanent delete models
// Route::delete('v2/notes/{id}', 'API\NotesController@softDelete');
// Route::get('v2/notes/withsoftdelete','API\NotesController@notesWithSoftDelete');
// Route::get('v2/notes/softdeleted','API\NotesController@softDeleted');
// Route::patch('/v1/notes/{id}','API\NotesController@restore');
// Route::delete('v3/notes/{id}','API\NotesController@permanentDeleteSoftDeleted');
Route::put('/notes/{user}', ['as'=>'users.update', 'uses'=>'API\APIController@update']);