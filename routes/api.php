<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Job\JobPostController;
use App\Http\Controllers\Job\JobResponseController;

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

//end public routes

//protected routes
Route::group(['middleware' => ['auth:sanctum'] ],function() {
    Route::post('/job-response',[JobResponseController::class,'store']);
    Route::post('job-vacancy',[JobPostController::class,'store']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//end protected routes
