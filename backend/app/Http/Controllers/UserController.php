<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // if receive id(number) = return currentUser data // if receive gender then return data of users with this gender to show at home
    function getUsers(Request $request)
    {
        $id = $request->id;
        // if there is an id provided (GetUser) - profile page
        $currentUser_interest = User::where('id', $id)->get(['interested_in']);
        if (!count($currentUser_interest)>0) {
            return response()->json([
                'status' => 'Error',
                'data' => 'User Not Found',
            ]);
        }

        $users = User::where('gender', $currentUser_interest[0]->interested_in)->get();
        if (count($users) > 0) {
            return response()->json([
                'status' => 'Success',
                'data' => $users,
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'data' => 'Users Not Found',
        ]);
    }
}
