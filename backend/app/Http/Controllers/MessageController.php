<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function sendMessage(Request $request, $shown_id)
    {
        $user = JWTAuth::authenticate($request->token);

        //validate empty message or no receiver id
        if (!$shown_id or !$request->message) return response()->json([
            'status' => 'Error',
            'data' => 'Message Not Sent',
        ]);

        $data = array(
            'sender_id' => $user->id, 'receiver_id' => $shown_id, 'text' => $request->message,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        );
        DB::table('messages')->insert($data);
        return response()->json([
            'status' => 'Success',
            'data' => 'Message Sent!'
        ]);
    }

    function getMessages(Request $request)
    {
        $user = JWTAuth::authenticate($request->token);

        $messages_sent = DB::table('users')->join('messages', 'users.id', '=', 'messages.sender_id')->get(); //get messages of currentUser
        $messages_received = DB::table('users')->join('messages', 'users.id', '=', 'messages.receiver_id')->get(); //get messages of currentUser


        // response for user own profile
        return response()->json([
            'status' => 'Success',
            'messages_sent' => $messages_sent,
            'messages_received' => $messages_received,
        ]);
    }

    function getSingleChat(Request $request, $shown_id){
        $user = JWTAuth::authenticate($request->token);

        if (!$shown_id) return response()->json([
            'status' => 'Error',
            'data' => 'User Not Found',
        ]);

        
        $messages = DB::table('messages')->where('sender_id',$user->id)->where('receiver_id',$shown_id)->orWhere('sender_id',$shown_id)->where('receiver_id',$user->id)->get();
        return response()->json([
            'status' => 'Success',
            'messages' => $messages,
        ]);
    }
}
