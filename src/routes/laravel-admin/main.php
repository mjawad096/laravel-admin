<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Dotlogics\Admin\App\Http\Controllers',
    'middleware' => config('laravel-admin.router.middleware_web', 'web'),
    'prefix' => config('laravel-admin.router.prefix', 'admin'),
    'as' => 'laravel-admin.',
], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('do_login');
    Route::post('logout', 'Auth\LoginController@logout')->middleware('auth')->name('logout');

    // Registration Routes...
    if ($options['register'] ?? false) {
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register')->name('do_register');
    }

    // Password Reset Routes...
    if ($options['reset'] ?? false) {
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    }

    // Password Confirmation Routes...
    if ($options['confirm'] ?? false) {
        Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
        Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm')->name('password.do_confirm');
    }

    // Email Verification Routes...
    if ($options['verify'] ?? false) {
        Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
    }
});
