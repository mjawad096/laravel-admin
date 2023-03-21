<?php

namespace Dotlogics\Admin\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('laravel-admin::auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        $dashboard_url = config('laravel-admin.dashboard_url', '/');
        $dashboard_route = config('laravel-admin.routes.dashboard');

        if (! empty($dashboard_route)) {
            $dashboard_url = route($dashboard_route);
        }

        return $dashboard_url;
    }
}
