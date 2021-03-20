<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all() , [
            'text' => 'required|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., -.?؟:@#?؟!،\n]+$/u',
        ]);

        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }

            $comment = new Comment;
            $comment->user_id = Auth::user()->id;
            $comment->user_id_token = Auth::user()->id_token;
            $comment->article_id = $request->article_id;
            $comment->text = $request->text;
            $comment->parent_id = $request->parent_id;
            $comment->save();

            return response()->json([
                'status' => 200,
                'message' => 'نظر با موفقیت ویرایش شد',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }

    public function getCommnts(Request $request){
        $data = new CommentCollection(Comment::orderBy('id' , 'DESC')->get());
        return $data->all();
    }

    public function delete(Request $request){
        try {
            Comment::where('id' , $request->id)->orWhere('parent_id' , $request->id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'نظر با موفقیت حذف شد',
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
            $comment = Comment::where('id' , $request->id)->first();
            $comment->is_active = ($comment->is_active - 1)*(-1);
            $comment->save();
            return response()->json([
                'status' => 200,
                'message' => 'عملیات با موفقیت انجام شد',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }
}
