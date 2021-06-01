<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'App\Http\Controllers',
    'middleware' => config('laravel-admin.router.middleware_web', 'web'),
    'prefix'     => config('laravel-admin.router.prefix', 'admin'),
    'as'     => 'laravel-admin.',
], function(){
	
});