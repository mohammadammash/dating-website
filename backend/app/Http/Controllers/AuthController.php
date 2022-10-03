<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public $token = true;

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
        $hashedpassword = bcrypt($request->password);

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
        if ($this->token) {
            return $this->loginUser($request);
        }

    }

    function loginUser(Request $request)
    {
        $email = $request->email;
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $email)->get();
        if (count($user) > 0) {
            return response()->json([
                'status' => 'Success',
                'data' => $user,
                'token' => $jwt_token,
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'data' => 'User Not Found',
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
