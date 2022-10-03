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
            'data'=>'Message Not Sent',
        ]);

        $data = array(
            'sender_id' => $user->id, 'receiver_id' => $shown_id, 'text'=>$request->message,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        );
        DB::table('messages')->insert($data);
        return response()->json([
            'status'=> 'Success',
            'data'=> 'Message Sent!'
        ]);
    }
}
