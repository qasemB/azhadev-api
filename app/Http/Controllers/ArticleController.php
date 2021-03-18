<?php

namespace App\Http\Controllers;

use App\Http\Resources\Article as ResourcesArticle;
use App\Http\Resources\ArticleCollection;
use App\Models\Article;
use App\Models\Token;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getAllArticles(){
        $data =  new ArticleCollection(Article::where('is_active' ,1)->orderBy('id' , 'DESC')->get());
        return $data->all();
    }

    public function getGroupOfArticles($catId){
        return Article::where([
            ['is_active' ,1],
            ['category_id' , (int)$catId]
        ])->orderBy('id' , 'DESC')->get();
    }

    public function getSingleArticle($articleId){
        return new ResourcesArticle(Article::where('id' , (int)$articleId)->first());
    }
}
