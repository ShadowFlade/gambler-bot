<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller
{
	public function index(Request $request)
	{
		return view('welcome');
	}
	public function releases(Request $request)
	{
		return view('releases');
	}
	public function fuckYou(Request $request)
	{
		return view('fuck_you');
	}
}
