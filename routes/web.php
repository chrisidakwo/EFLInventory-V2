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

Auth::routes();

Route::get('/', static function () {
    return redirect()->route("home");
});

Route::group(["middleware" => "auth"], static function () {
    Route::get('/dashboard', 'HomeController@index')->name('home');
});

// Categories
Route::group(["middleware" => "auth"], static function () {
    Route::get('/categories', 'Common\CategoryController@index')->name('categories');
});

Route::group(["prefix" => "category", "middleware" => ["auth"]], static function () {
    Route::get('/new', 'HomeController@index')->name('category.create');
    Route::post('', 'HomeController@index')->name('category.store');
    Route::put('/{category}/update', 'HomeController@index')->name('category.update');
    Route::delete('/{category}/delete', 'HomeController@index')->name('category.delete');
});


// Users
Route::group(["middleware" => "auth"], static function () {
    Route::get('/users', 'HomeController@index')->name('users');
});

Route::group(["prefix" => "user", "middleware" => ["auth"]], static function () {
    Route::get('/new', 'HomeController@index')->name('user.create');
    Route::post('', 'HomeController@index')->name('user.store');
    Route::put('/{user}/update', 'HomeController@index')->name('user.update');
    Route::delete('/{user}/delete', 'HomeController@index')->name('user.delete');
});


// Inventory
Route::group(["prefix" => "inventory", "middleware" => ["auth"]], static function () {
   Route::get("", "Inventory\InventoryController@index")->name("inventory");
});


// POS
Route::group(["prefix" => "pos", "middleware" => ["auth"]], static function () {
    Route::get("", "Inventory\POSController@index")->name("pos");
});
