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

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/tt', function () {
//    return view('RumiTest');
//});

/*rumi test */

Route::get('test/rumi/{from}/{start}','TestController@testRumi');
Route::get('rumi','TestController@Rumi');

Route::get('faruk','EmployeeController@updateJoinInfo');

//Route::get('test/rumi/{from}/{start}','TestController@testRumi');

Route::get('rumi','TestController@Rumi');

Route::get('rumi1','TestController@Rumi1');


Route::get('/clear-cache', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

   // return redirect('/');
    return "Cache is cleared";
});


