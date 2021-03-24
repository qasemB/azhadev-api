<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OthersControllers;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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



Route::post('/register' , [UserController::class , 'register'])->middleware('checkToken');
Route::post('/login' , [UserController::class , 'login'])->middleware('checkToken');
Route::post('/logout' , [UserController::class , 'logout']);
Route::post('/forget-password' , [UserController::class , 'forgetPassword'])->middleware('checkToken');;

Route::group(['middleware' => ['auth:sanctum']] , function(){
    Route::post('/check-login' , [UserController::class , 'checkLogin']);
    Route::post('/get-user' , function(){return Auth::user();});
    Route::post('/admin/store-category' , [CategoryController::class , 'store'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/delete-category' , [CategoryController::class , 'delete'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/edit-category' , [CategoryController::class , 'edit'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/all-articles' , [ArticleController::class , 'getAdminAllArticles'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/store-article' , [ArticleController::class , 'store'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/active-article' , [ArticleController::class , 'active'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/delete-article' , [ArticleController::class , 'delete'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/edit-article' , [ArticleController::class , 'edit'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/get-comments' , [CommentController::class , 'getCommnts'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/delete-comment' , [CommentController::class , 'delete'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/active-comment' , [CommentController::class , 'active'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/store-comment' , [CommentController::class , 'store'])->middleware('checkToken');
    Route::post('/admin/all-users' , [UserController::class , 'getAllUsers'])->middleware(['checkAdmin' , 'checkToken']);
    Route::post('/admin/delete-user' , [UserController::class , 'delete'])->middleware(['checkAdmin' , 'checkToken']);
});


Route::post('/all-articles' , [ArticleController::class , 'getAllArticles'])->middleware('checkToken');
Route::post('/all-articles/{catId}' , [ArticleController::class , 'getGroupOfArticles'])->middleware('checkToken');
Route::post('/article/{articleId}' , [ArticleController::class , 'getSingleArticle'])->middleware('checkToken');
Route::post('/all-categories' , [CategoryController::class , 'getAllCtegories'])->middleware('checkToken');
Route::post('/get-things' , [OthersControllers::class , 'getThings'])->middleware('checkToken');
Route::post('/get-keywords' , [OthersControllers::class , 'getKeywords'])->middleware('checkToken');
Route::post('/get-abilities' , [OthersControllers::class , 'getAbilities'])->middleware('checkToken');
