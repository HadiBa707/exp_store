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
    Route::post('create/product', [App\Http\Controllers\User\UserProductController::class, 'store']); //TODO
    Route::put('update/product/{product}', [App\Http\Controllers\User\UserProductController::class, 'update']); //TODO
    Route::delete('delete/product/{product}', [App\Http\Controllers\User\UserProductController::class, 'destroy']);
    Route::get('likes', [App\Http\Controllers\User\UserLikeController::class, 'index']);
});
Route::put('users/update', 'App\Http\Controllers\User\UserController@update');


/**
 *Products
 */
Route::resource('products', App\Http\Controllers\Product\ProductController::class, ['only' => ['index', 'show']]);
Route::get('products/{product}/categories', [App\Http\Controllers\Product\ProductCategoryController::class, 'index']);

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
Route::get('products/{product}/comments', [App\Http\Controllers\Comment\CommentController::class, 'index']);
Route::post('products/{product}/create/comment', [App\Http\Controllers\Comment\CommentController::class, 'addComment']);
Route::delete('products/delete/comments/{comment}', [App\Http\Controllers\Comment\CommentController::class, 'deleteComment']);
