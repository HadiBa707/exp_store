<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

//    'middleware' => 'api',
    'prefix' => 'auth',

], function () {

    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

});

/**
 *Users
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('products', [App\Http\Controllers\User\UserProductController::class, 'index']);
    Route::post('create/product', [App\Http\Controllers\User\UserProductController::class, 'store']);
    Route::put('update/product/{product}', [App\Http\Controllers\User\UserProductController::class, 'update']);
    Route::delete('delete/product/{product}', [App\Http\Controllers\User\UserProductController::class, 'destroy']);
});
Route::put('users/update', 'App\Http\Controllers\User\UserController@update');

/**
 *Products
 */
Route::resource('products', App\Http\Controllers\Product\ProductController::class, ['only' => ['index', 'show']]);

/**
 *Categories
 */
Route::resource('categories', App\Http\Controllers\Category\CategoryController::class, ['except' => ['edit', 'create']]);

/**
 *Likes
 */
Route::post('products/{product}/like', [App\Http\Controllers\Like\LikeController::class, 'addLike']);
Route::delete('products/{product}/deleteLike', [App\Http\Controllers\Like\LikeController::class, 'deleteLike']);

/**
 *Comments
 */
Route::post('products/{product}/comment', [App\Http\Controllers\Comment\CommentController::class, 'addComment']);
Route::delete('products/{product}/deleteComment', [App\Http\Controllers\Comment\CommentController::class, 'deleteComment']);
