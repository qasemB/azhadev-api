<?php

namespace App\Http\Controllers;

use App\Http\Resources\Article as ResourcesArticle;
use App\Http\Resources\ArticleCollection;
use App\Models\Article;
use App\Models\Keyword;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function getAllArticles(){
        $data =  new ArticleCollection(Article::where('is_active' ,1)->orderBy('id' , 'DESC')->get());
        return $data->all();
    }
    public function getAdminAllArticles(){
        $data =  new ArticleCollection(Article::orderBy('id' , 'DESC')->get());
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

    public function store(Request $request){
        $validator = Validator::make($request->all() , [
            'category_id' => 'required|numeric',
            'h_title' => 'required|unique:articles,h_title|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'top_title' => 'required|unique:articles,h_title|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'top_text' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'text' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'image' => 'required',
            'alt_image' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'keywords' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
        ]);
        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }
            $article = new Article;
            $article->category_id = $request->category_id;
            $article->writer_id = Auth::user()->id;
            $article->writer_name = Auth::user()->firstname.' '.Auth::user()->lastname;
            $article->h_title = $request->h_title;
            $article->top_title = $request->top_title;
            $article->top_text = $request->top_text;
            $article->text = $request->text;
            $article->image = $request->image;
            $article->alt_image = $request->alt_image;
            $article->keywords = $request->keywords;
            $article->save();

            $keywords = explode(',' , $request->keywords);
            foreach ($keywords as $k) {
                Keyword::create([
                    'keyword' => $k,
                    'for' => $article->id,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'دسته با موفقیت ایجاد شد',
                'data' => $article,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }

    public function active(Request $request){
        try {
            $article = Article::find((int)$request->id);
            $article->is_active = ($article->is_active-1) * (-1);
            $article->save();
            return response()->json([
                'status' => 200,
                'message' => "مقاله با موفقیت فعال شد"
            ]) ;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }

    public function delete(Request $request){
        try {
            Article::where('id' , $request->id)->delete();
            Keyword::where('for' , $request->id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'مقاله با موفقیت حذف شد'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }


    public function edit(Request $request){
        $validator = Validator::make($request->all() , [
            'category_id' => 'required|numeric',
            'h_title' => 'required|unique:articles,h_title,'.$request->id.'|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'top_title' => 'required|unique:articles,h_title,'.$request->id.'|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'top_text' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'text' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'image' => "required",
            'alt_image' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
            'keywords' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#!،\n]+$/u',
        ]);
        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }
            $article = Article::find((int)$request->id);
            $article->category_id = $request->category_id;
            $article->h_title = $request->h_title;
            $article->top_title = $request->top_title;
            $article->top_text = $request->top_text;
            $article->text = $request->text;
            $article->image = $request->image;
            $article->alt_image = $request->alt_image;
            $article->keywords = $request->keywords;
            $article->save();

           $article =  new ResourcesArticle(Article::where('id' , (int)$request->id)->first());

            return response()->json([
                'status' => 200,
                'message' => 'دسته با موفقیت ویرایش شد',
                'data' => $article,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }

}
