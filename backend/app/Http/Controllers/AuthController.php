<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

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
        //profile password should be base64 -> retrieve it back to image:
        $image_64 = $validator['profile_url'];
        $email = $validator['email'];
        $targetPath = public_path() . '\user_images';
        $image_url = $targetPath . "\\" . $email . ".jpeg";
        $this->base64_to_jpeg($image_64, $image_url);

        // create new user object
        $new_user = User::create([
            'name' => $validator['name'],
            'password' => $hashedpassword,
            'profile_url' => $image_url,
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

    public function base64_to_jpeg($base64_string, $output_file)
    {
        // open the output file for writing
        // $ifp = fopen($output_file, 'wb');
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        // we could add validation here with ensuring count( $data ) > 1
        $image = base64_decode($base64_string);
        file_put_contents($output_file, $image);
    }
}
