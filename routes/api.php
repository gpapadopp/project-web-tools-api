<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\RolesController;
use \App\Http\Controllers\CoursesController;
use \App\Http\Controllers\CourseTypesController;

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

Route::controller(UsersController::class)->middleware(['cors'])->group(function() {
    Route::get("users/", 'index');
    Route::post("users/login/", 'login');
    Route::get('users/{id}', 'specific');
    Route::post('users/verify', 'verify');
    Route::post('users/add', 'add');
    Route::post('users/{id}', 'update');
    Route::delete('users/{id}', 'delete');
    Route::post('users/public/add', 'register');
});

Route::controller(RolesController::class)->middleware(['cors'])->group(function() {
    Route::get('roles/', 'index');
    Route::get('roles/{id}', 'specific');
    Route::post('roles/add', 'add');
    Route::post('roles/{id}', 'update');
    Route::delete('roles/{id}', 'delete');
});

Route::controller(CoursesController::class)->middleware(['cors'])->group(function() {
    Route::get("courses/", 'index');
    Route::get('courses/{id}', 'specific');
    Route::post('courses/add', 'add');
    Route::post('courses/{id}', 'update');
    Route::delete('courses/{id}', 'delete');
});

Route::controller(CourseTypesController::class)->middleware(['cors'])->group(function() {
    Route::get("course-types/", 'index');
    Route::get('course-types/{id}', 'specific');
    Route::post('course-types/add', 'add');
    Route::post('course-types/{id}', 'update');
    Route::delete('course-types/{id}', 'delete');
});
