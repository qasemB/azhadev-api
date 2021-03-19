<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getAllCtegories(){
        return Category::all();
    }



    public function store(Request $request){
        $validator = Validator::make($request->all() , [
            'title' => 'required|unique:categories,title|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.:@#!،\n]+$/u',
            'description' => 'nullable|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.:@#!،\n]+$/u',
            'icon' => 'required|unique:categories,icon|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.:@#!،\n]+$/u',
        ]);
        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }
            $category = new Category;
            $category->title = $request->title;
            $category->description = $request->description;
            $category->icon = $request->icon;
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'دسته با موفقیت ایجاد شد',
                'data' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }



    public function delete(Request $request){
        try {
            Category::where('id' , $request->id)->delete();
            Article::where('category_id' , $request->id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'دسته با موفقیت حذف شد'
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
            'title' => "required|unique:categories,title,$request->id",
            'description' => 'nullable',
            'icon' => "required|unique:categories,icon,$request->id",
        ]);
        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }
            $category = Category::find($request->id);
            $category->title = $request->title;
            $category->description = $request->description;
            $category->icon = $request->icon;
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'دسته با موفقیت ویرایش شد',
                'data' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }
}
