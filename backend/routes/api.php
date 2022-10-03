<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;

// LANDING PAGE ROUTES:
Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');

// HOME PAGE ROUTES:
Route::group(['middleware' => 'jwt.auth'], function () {
    //log user out remove the token
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout-user');

    // show home (get interested gender users - show user own profile)
    Route::get("/home", [UserController::class, "getUsers"])->name("get-users");
    Route::get("/home/profile", [UserController::class, "getCurrentUser"])->name("get-user"); //get also all messages received or sent to show in profile page

    //specific user - show on click - block or favorite functionality
    Route::get('/home/{shown_id?}', [UserController::class, 'getUser'])->name('get-specific-user');
    Route::post('/home/{shown_id?}', [UserController::class, 'blockOrFollowUser'])->name('block-or-follow-user');

    // send a specific message to a user
    Route::post('/home/{shown_id?}/chat', [MessageController::class, 'sendMessage'])->name('send-message');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
