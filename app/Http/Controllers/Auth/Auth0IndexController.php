<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Auth0IndexController extends Controller
{
    /**
     * Redirect to the Auth0 hosted login page
     *
     * @return mixed
     */
    public function login()
    {
        $authorizeParams = [
            'scope' => 'openid profile email',
            // Use the key below to get an access token for your API.
            'audience' => config('laravel-auth0.api_identifier'),
        ];

        // Auth0Service のインスタンスにログイン処理を任せる
        return app()->make('auth0')->login(null, null, $authorizeParams);

        // Auth0 のアプリケーション固有の認証ページにリダイレクトされる
    }

    /**
     * Log out of Auth0
     *
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();

        $logoutUrl = sprintf(
            'https://%s/v2/logout?client_id=%s&returnTo=%s',
            config('laravel-auth0.domain'),
            config('laravel-auth0.client_id'),
            url('/')
        );

        return redirect()->intended($logoutUrl);
    }
}
