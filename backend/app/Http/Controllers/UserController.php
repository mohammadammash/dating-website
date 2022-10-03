<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    // if receive id(number) = return currentUser data // if receive gender then return data of users with this gender to show at home
    function getUsers(Request $request)
    {
        //get user if valid from JWTAuth
        $user = JWTAuth::authenticate($request->token);

        // if there is an id provided (Get the user interested gender)
        $currentUser_interest = User::where('id', $user->id)->get(['interested_in']);
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
    function getUser(Request $request, $shown_id = '')
    {
        $user = JWTAuth::authenticate($request->token);
        $messages = [];

        $id = $user->id;
        // if we are getting specific user show data, save current id and retrieve shown id data
        if ($shown_id) {
            $user_id = $id; //store id to check if blocked or favorited user
            $id = $shown_id;
        } else $messages = DB::table('messages')->where('sender_id', $id)->orWhere('receiver_id', $id)->get(); //get messages of currentUser

        // if there is an id provided (GetUser) - profile page
        $currentUser = User::where('id', $id)->get();
        if (!count($currentUser) > 0) {
            return response()->json([
                'status' => 'Error',
                'data' => 'User Not Found',
            ]);
        }

        //get specific user not currentUser: //check if he is blocked first or favorited: to show buttons in a way according to the user state
        if ($shown_id) {
            $is_blocked = $this->checkIfBlocked($user_id, $shown_id);
            $is_favorited =  $this->checkIfFavorited($user_id, $shown_id);
            // temp:
            $temp = $user_id;
            $user_id = $shown_id;
            $shown_id= $temp;
            $blocked_by = $this->checkIfBlocked($user_id, $shown_id);
            return response()->json([
                'status' => 'Success',
                'data' => $currentUser,
                'is_favorited' => $is_favorited,
                'is_blocked' => $is_blocked,
                'blocked_by'=> $blocked_by,
            ]);
        }

        // response for user own profile
        return response()->json([
            'status' => 'Success',
            'data' => $currentUser,
            'messages' => $messages,
        ]);
    }

    //function to handle follow or block for a user:
    function blockOrFollowUser($shown_id, Request $request)
    {
        $user = JWTAuth::authenticate($request->token);
        $id = $user->id;
        $state = $request->state;

        if ($state === 'favorite') {
            // adding user to favorited users:
            // check if favorited first, then if not favorited check if blocked
            if ($this->checkIfFavorited($id, $shown_id) or $this->checkIfBlocked($id, $shown_id)) {
                return response()->json([
                    'status' => 'Error',
                    'data' => 'Action Denied',
                ]);
            }
            $data = array(
                'user_id' => $id, 'favorited_id' => $shown_id,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            );
            DB::table('favorited_users')->insert($data);
            return response()->json([
                'status' => 'Success',
                'data' => 'Favorited Successfully!',
            ]);
        } else if ($state === 'unfavorite') {
            // unfavorite user (remove the relation from favorited_users table)
            $favorite_exist = DB::table('favorited_users')->where('user_id', $id)->where('favorited_id', $shown_id)->get();

            if (count($favorite_exist) > 0) {
                DB::table('favorited_users')->where('user_id', $id)->where('favorited_id', $shown_id)->delete();
                return response()->json([
                    'status' => 'Success',
                    'data' => 'Favorite Removed',
                ]);
            }
            return response()->json([
                'status' => 'Error',
                'data' => 'Favorite not found',
            ]);
        } else if ($state === 'block') {
            // adding user to blocked users:
            // check if user already blocked:
            if ($this->checkIfBlocked($id, $shown_id)) {
                return response()->json([
                    'status' => 'Error',
                    'data' => 'Already Blocked!',
                ]);
            }
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

            if (count($block_exist) > 0) {
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

    // function to check if shown user is favorited
    function checkIfFavorited($id, $shown_id)
    {
        if (!$id or !$shown_id) return response()->json([
            'status' => 'Error',
            'data' => "Error Finding Users!!"
        ]);

        $is_favorited = DB::table('favorited_users')->where('user_id', $id)->where('favorited_id', $shown_id)->get();
        if (count($is_favorited) > 0) $is_favorited = true;
        else $is_favorited = false;

        return $is_favorited;
    }

    //function to check if blocked user
    function checkIfBlocked($id, $shown_id)
    {
        if (!$id or !$shown_id) return response()->json([
            'status' => 'Error',
            'data' => "Error Finding Users!!"
        ]);

        $is_blocked = DB::table('blocked_users')->where('user_id', $id)->where('blocked_id', $shown_id)->get();
        if (count($is_blocked) > 0) $is_blocked = true;
        else $is_blocked = false;
        return $is_blocked;
    }
}
