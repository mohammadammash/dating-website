<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // if receive id(number) = return currentUser data // if receive gender then return data of users with this gender to show at home
    function getUserOrUsers($id = 'none')
    {
        // if there is an id provided (GetUser) - profile page
        if (is_numeric($id)) {
            $currentUser = User::where('id', $id)->get();
            if (count($currentUser) > 0) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $currentUser,
                ]);
            }

            return response()->json([
                'status' => 'Error',
                'data' => 'User Not Found',
            ]);

        //if there is no id
        } else { 
            return '';
        }
    }
}
