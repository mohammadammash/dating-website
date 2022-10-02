<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// LANDING PAGE ROUTES:
Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');

// HOME PAGE ROUTES:
Route::get('/home/{id?}', [UserController::class, 'getUserOrUsers'])->name('get-user-or-users');
Route::get('/home/id/{state?}', [UserController::class, 'blockOrFollowUser'])->name('block-or-follow-user');

// MESSAGES ROUTES:
Route::get('/home/id/chat', [MessagesController::class, 'getMessages'])->name('get-messages');
Route::post('/home/id/chat', [MessagesController::class, 'sendMessage'])->name('send-message');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
