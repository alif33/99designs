<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ReportController;
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

    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/verify', [MailController::class, 'verification']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::post('/profile', [UserController::class, 'profile']);

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

// Admin Dashboard

Route::group([
    'prefix' => 'admin'

], function () use ($router) {

    Route::get('/contests', [AuthorityController::class, 'get_contests']);
    Route::put('/contest/active/{id}', [AuthorityController::class, 'active']);
    Route::put('/contest/deactive/{id}', [AuthorityController::class, 'deactive']);
    Route::get('/stories', [AuthorityController::class, 'get_stories']);
    Route::post('/story', [AuthorityController::class, 'add_story']);
    Route::put('/story/active/{id}', [AuthorityController::class, 'active_story']);
    Route::put('/story/deactive/{id}', [AuthorityController::class, 'deactive_story']);

});



// Category

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::post('/category', [CategoryController::class, 'store']);
        Route::put('/category/{id}', [CategoryController::class, 'update']);
        Route::delete('/category/{id}', [CategoryController::class, 'destory']);
    }
);


// Contest

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('/contests', [ContestController::class, 'index']);
        Route::get('/contests/fetch', [ContestController::class, 'fetch']);
        Route::get('/contest/id/{id}', [ContestController::class, 'show']);
        Route::post('/contest/{slug}', [ContestController::class, 'find']);
        Route::post('/contest', [ContestController::class, 'store']);
        Route::put('/contest/{id}', [ContestController::class, 'update']);
        Route::delete('/contest/{id}', [ContestController::class, 'destory']);
    }
);

// Story

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('/stories', [StoryController::class, 'index']);
        Route::get('/stories/random', [StoryController::class, 'random']);
        Route::get('/stories/fetch', [StoryController::class, 'fetch']);
        Route::get('/stories/search/{search}', [StoryController::class, 'search']);
        Route::get('/stories/likes/{id}', [StoryController::class, 'likes']);
        Route::post('/stories/like/{id}', [StoryController::class, 'like']);
        Route::post('/stories/unlike/{id}', [StoryController::class, 'unlike']);
        Route::get('/stories/bookmarks', [StoryController::class, 'bookmarks']);
        Route::post('/stories/bookmark/{id}', [StoryController::class, 'bookmark']);
        Route::post('/stories/hasbookmarked/{id}', [StoryController::class, 'hasbookmarked']);
        Route::post('/stories/unbookmark/{id}', [StoryController::class, 'unbookmark']);
        Route::post('/story/{slug}', [StoryController::class, 'find']);
        Route::post('/story', [StoryController::class, 'store']);
        Route::put('/story/{id}', [StoryController::class, 'update']);
        Route::delete('/story/{id}', [StoryController::class, 'destory']);
    }
);

// Comments

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::get('/story/comments/{id}', [CommentController::class, 'index']);
        Route::post('/story/comment/{id}', [CommentController::class, 'store']);
    }
);


// Reports

Route::group(
    [
        'prefix' => '/'
    ],
    function ($router) {
        Route::post('/story/report/{id}', [ReportController::class, 'store']);
    }
);

