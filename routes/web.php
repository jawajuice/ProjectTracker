<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
   dispatch(function () {
   	logger('Hello there');
	});

return 'Finished';

});

Auth::routes();
Route::get('/task', 'ProjectsController@task');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/update', 'ProjectsController@update')->name('update');
Route::get('/users', 'AuthController@users')->name('users');
Route::get('/send', 'ProjectsController@send');

Route::get('/demo', function () {
    return new App\Mail\WeeklyOverview();
});