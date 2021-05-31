<?php

use Illuminate\Support\Facades\Route;


Route::prefix('laravel-admin')->name('laravel-admin.')->namespace('Topdot\Admin\App\Http\Controllers')->group(function(){
	// 	Route::get('{media}', 'MediaController@show')->name('show');
});