<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // if receive id(number) = return currentUser data // if receive gender then return data of users with this gender to show at home
    function getUsers($id)
    {
        // if there is an id provided (Get the user interested gender)
        $currentUser_interest = User::where('id', $id)->get(['interested_in']);
        if (!count($currentUser_interest) > 0) {
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

    //get user profile data to show in his profile page
    function getUser($id = '', $shown_id = '')
    {
        if($shown_id) $id = $shown_id;

        // if there is an id provided (GetUser) - profile page
        $currentUser = User::where('id', $id)->get();
        if (!count($currentUser) > 0) {
            return response()->json([
                'status' => 'Error',
                'data' => 'User Not Found',
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'data' => $currentUser,
        ]);
    }

    //function to handle follow or block for a user:
    function blockOrFollowUser($id, $shown_id, Request $request){
        return [$id,$shown_id,$request->state];
    }
}
