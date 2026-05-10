<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'title' => 'required|email',
            'body' => 'required',
        ]);
        dd($validated->fails());

    }
}
