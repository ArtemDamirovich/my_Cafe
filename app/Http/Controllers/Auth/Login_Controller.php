<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class Login_Controller extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dash_board';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
