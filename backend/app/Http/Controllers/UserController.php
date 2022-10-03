<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        if ($shown_id) $id = $shown_id;

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
    function blockOrFollowUser($id, $shown_id, Request $request)
    {
        $state = $request->state;
        if ($state === 'favorite') {
            // adding user to favorited users:
            $data = array(
                'user_id' => $id, 'favorited_id' => $shown_id,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            );
            DB::table('favorited_users')->insert($data);
        } else if ($state === 'unfavorite') {
            return 'UNFOLLOW:(';

        } else if ($state === 'block') {
            // adding user to blocked users:
            $data = array(
                'user_id' => $id, 'blocked_id' => $shown_id,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            );
            DB::table('blocked_users')->insert($data);
            DB::table('blocked_users')->where('user_id', $id)->get();

            return response()->json([
                'status' => 'Success',
                'data' => 'User Blocked',
            ]);

        } else if ($state === 'unblock') {
            // unblock user (remove the relation from blocked_users table)
            $block_exist = DB::table('blocked_users')->where('user_id', $id)->where('blocked_id', $shown_id)->get();

            if (count($block_exist)>0) {
                DB::table('blocked_users')->where('user_id', $id)->where('blocked_id', $shown_id)->delete();
                return response()->json([
                    'status' => 'Success',
                    'data' => 'Block Removed',
                ]);
            }

            return response()->json([
                'status' => 'Error',
                'data' => 'Block not found',
            ]);
        } else {
            //undefine state given
            return response()->json([
                'status' => 'Error',
                'data' => 'State Not Found',
            ]);
        }
    }
}
