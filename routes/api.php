<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UsersController::class)->group(function() {
    Route::get("users/", 'index');
    Route::post("users/login/", 'login');
    Route::get('users/{id}', 'specific');
    Route::post('users/verify', 'verify');
    Route::post('users/add', 'add');
    Route::post('users/{id}', 'update');
    Route::delete('users/{id}', 'delete');
    Route::post('users/public/add', 'register');
});
