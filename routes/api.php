<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/all-articles' , [ArticleController::class , 'getAllArticles'])->middleware('checkToken');

Route::post('/all-articles/{catId}' , [ArticleController::class , 'getGroupOfArticles'])->middleware('checkToken');

Route::post('/article/{articleId}' , [ArticleController::class , 'getSingleArticle'])->middleware('checkToken');

Route::post('/all-categories' , [CategoryController::class , 'getAllCtegories'])->middleware('checkToken');
