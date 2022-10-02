<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    function registerUser(Request $request)
    {

        // validate request data (if not valid laravel will return by itself)
        $validator = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
            'profile_url' => 'required|string',
            'gender' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'interested_in' => 'required|string',
            'bio' => 'required|string',
            'age' => 'required|integer',
            'location' => 'required|string',
        ]);
        // encrypt password
        $hashedpassword = hash('sha256', $request->password . 'hugmecomeon');

        // create new user object
        $new_user = User::create([
            'name' => $validator['name'],
            'password' => $hashedpassword,
            'profile_url' => $validator['profile_url'],
            'gender' => $validator['gender'],
            'email' => $validator['email'],
            'interested_in' => $validator['interested_in'],
            'bio' => $validator['bio'],
            'age' => $validator['age'],
            'location' => $validator['location'],
        ]);
        $email = $validator['email'];
        $password = $validator['password'];
        return response()->json([
            'status' => 'Success',
            'email' => $email,
            'password' => $password,
        ]);
    }

    function loginUser(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $hashedpassword = hash('sha256', $password . 'hugmecomeon');
        $user = User::where('email', $email)->where('password', $hashedpassword)->get();
        if (count($user) > 0) {
            return response()->json([
                'status' => 'Success',
                'data' => $user,
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'data' => 'User Not Found',
        ]);
    }
}
