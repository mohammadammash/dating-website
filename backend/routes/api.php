<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// LANDING PAGE ROUTES:
Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');

// HOME PAGE ROUTES:
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout-user');
    Route::get("/home", [UserController::class, "getUsers"])->name("get-users");
    Route::get("/home/profile", [UserController::class, "getUser"])->name("get-user");
    Route::get('/home/{shown_id?}', [UserController::class, 'getUser'])->name('get-specific-user');
    Route::post('/home/{shown_id?}', [UserController::class, 'blockOrFollowUser'])->name('block-or-follow-user');

    // MESSAGES ROUTES:
    Route::get('/home/id/chat', [MessagesController::class, 'getMessages'])->name('get-messages');
    Route::post('/home/id/chat', [MessagesController::class, 'sendMessage'])->name('send-message');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
