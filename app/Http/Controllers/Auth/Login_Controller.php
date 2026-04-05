<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class Login_Controller extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dash_board';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function user_name()
    {
        return 'login';
    }

    protected function credentials(Request $request)
    {
        return [
            'login' => $request->login,
            'password' => $request->password,
            'status' => 'active'
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        if($user->is_Admin())
            {
                return redirect()->route('admin.dash_board');
            }
        if($user->is_Chef())
            {
                return redirect()->route('chef.dash_board');
            }
        if($user->is_Waiter())
            {
                return redirect()->route('waiter.dash_board');
            }
    }
}
