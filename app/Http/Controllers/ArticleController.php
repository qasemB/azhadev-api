<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Token;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getAllArticles(){
        // return response(
        //     Article::where('is_active' ,1)->orderBy('id' , 'DESC')->get(),
        //     201
        // );
        return Article::where('is_active' ,1)->orderBy('id' , 'DESC')->get();
    }
}
