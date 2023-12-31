<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', function () {
    return view('welcome');
})->name('login');

//Route::get('/{any}', 'Kiosk\MainController@index')->where('any', '.*')->name('kiosk.main');
//Route::get('/{id}', 'Kiosk\MainController@index')->where('any', '.*')->name('kiosk.main');
Route::get('/', 'Kiosk\MainController@index')->name('kiosk.main');
Route::get('/{id}', 'Kiosk\MainController@index')->where('id', '[0-9]+')->name('kiosk.main');

// Route::get('/{id}', 'Kiosk\MainController@setRoom')->where('path', '.+')->name('kiosk.main.set-room');

