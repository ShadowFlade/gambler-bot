<?php

namespace App\Http\Controllers;

use App\Service\Telegram\Router;
use Illuminate\Http\Request;

class Telegram extends Controller
{
    public function index(Request $request)
    {
        $router = new Router();
        $router->route($request);
    }
}
