<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\StoryController;

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


// User

Route::group([
    'prefix' => 'user'

], function () use ($router) {

    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('refresh', [UserController::class, 'refresh']);
    Route::post('profile', [UserController::class, 'profile']);

});


// Admin

Route::group([
    'prefix' => 'admin'

], function () use ($router) {

    Route::post('login', [AdminController::class, 'login']);
    Route::post('register', [AdminController::class, 'register']);
    Route::post('refresh', 'UserController@refresh');
    Route::post('me', 'UserController@me');

});



// Category

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('category/{id}', [CategoryController::class, 'show']);
        Route::post('category', [CategoryController::class, 'store']);
        Route::put('category/{id}', [CategoryController::class, 'update']);
        Route::delete('category/{id}', [CategoryController::class, 'destory']);
    }
);


// Contest

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('contests', [ContestController::class, 'index']);
        Route::get('contest/{id}', [ContestController::class, 'show']);
        Route::post('contest', [ContestController::class, 'store']);
        Route::put('contest/{id}', [ContestController::class, 'update']);
        Route::delete('contest/{id}', [ContestController::class, 'destory']);
    }
);

// Story

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('stories', [StoryController::class, 'index']);
        Route::get('story/{id}', [StoryController::class, 'show']);
        Route::post('story', [StoryController::class, 'store']);
        Route::put('story/{id}', [StoryController::class, 'update']);
        Route::delete('story/{id}', [StoryController::class, 'destory']);
    }
);
