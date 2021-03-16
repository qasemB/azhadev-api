<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request){

        $validator=Validator::make($request->all() , [
            'firstname' =>  'string|required|max:255|regex:/^[ا-یa-zA-Z0-9\-0-9ء-ئ., ؟!.،]+$/u',
            'email' =>  'email|required|max:255|unique:users,email',
            'password' => 'required|confirmed|regex:/^[a-zA-z0-9]+$/u|string|min:8' ,
        ]);

        try {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422 ,
                    'message' => $validator->errors(),
                ]) ;
            }else{
                $user = new User;
                $user->id_token = Str::random(60);
                $user->email = $request['email'];
                $user->password = Hash::make($request['password']);
                $user->save();
                return response()->json([
                    'status' => 200,
                    'data' => $user
                ]) ;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }

    }
}
