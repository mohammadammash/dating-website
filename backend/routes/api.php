<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;

// AUTHENTICATON
Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');

// JWT MIDDLEWARE AUTHENTICATION NEEDED TO ACCESS NESTED ROUTES
Route::group(['middleware' => 'jwt.auth'], function () {

    // USER
    Route::get("/home", [UserController::class, "getUsers"])->name("get-users"); //get interested gender users to show in home page
    Route::post('/home/{shown_id?}', [UserController::class, 'blockOrFollowUser'])->name('block-or-follow-user'); //block-unblock or favorite-unfavorite specific user

    // MESSAGES
    Route::get("/home/chats", [MessageController::class, "getMessages"])->name("get-messages"); //get messages sent or received by a user
    Route::get('/home/chats/{shown_id?}', [MessageController::class, 'getSingleChat'])->name('get-chat'); //get all messages between two users
    Route::post('/home/chats/{shown_id?}', [MessageController::class, 'sendMessage'])->name('send-message'); //send a single message from a user to another
});

