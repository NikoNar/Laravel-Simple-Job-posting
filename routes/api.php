<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Job\JobPostController;
use App\Http\Controllers\Job\JobResponseController;
use App\Http\Controllers\Like\LikeController;
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

//public routes
Route::controller(UserController::class)->group(function(){
    Route::post('/user','store');
    Route::post('/user-login','login');
});

Route::get('/like-count/{object_id}',[LikeController::class,'getCount']);
Route::get('/job-vacancy',[JobPostController::class,'index']);
Route::get('/job-vacancy/{id}',[JobPostController::class,'show']);

//end public routes

//protected routes
Route::group(['middleware' => ['auth:sanctum'] ],function() {

    Route::get('/user',[UserController::class,'index']);
    Route::get('/user/{user}',[UserController::class,'show']);

    Route::post('/job-response',[JobResponseController::class,'store']);
    Route::delete('/job-response/{response}',[JobResponseController::class,'delete']);

    Route::post('/like-user/{user}',[LikeController::class,'likeUser']);
    Route::post('/like-job/{jobPost}',[LikeController::class,'likeJob']);

    Route::post('/job-vacancy',[JobPostController::class,'store']);
    Route::put('/job-vacancy/{post}',[JobPostController::class,'update']);
    Route::delete('/job-vacancy/{post}',[JobPostController::class,'delete']);
    Route::delete('/job-vacancy/force/{id}',[JobPostController::class,'forceDelete']);
});


//end protected routes
