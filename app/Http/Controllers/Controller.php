<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController; // Важно!
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController // Наследуем от BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}