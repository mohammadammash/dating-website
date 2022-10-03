<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user = JWTAuth::authenticate($request->token);

        return $user->id;
    }
}
