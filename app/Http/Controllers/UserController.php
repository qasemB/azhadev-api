<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            }

            $user = new User;
            $user->id_token = Str::random(60);
            $user->firstname = $request['firstname'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->save();
            return response()->json([
                'status' => 200,
                'data' => $user
            ]) ;

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }






    public function login(Request $request){
        $validator=Validator::make($request->all() , [
            'email' =>  'email|required',
            'password' => 'required|regex:/^[a-zA-z0-9]+$/u|string|min:8' ,
        ]);
        try {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422 ,
                    'message' => $validator->errors(),
                ]) ;
            }
            $credentials = request(['email' , 'password']);
            if (!Auth::attempt($credentials , $request->remember)) {
                return response()->json([
                    'status' => 401 ,
                    'message' => "ورود موفق نبود",
                ]);
            }
            $user = User::select('id' , 'id_token' , 'firstname' , 'lastname')->where('email' , $request["email"])->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => $tokenResult,
                'user' => $user,
            ]) ;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th
            ]) ;
        }
    }






    public function logout(Request $request){
        try {
            Auth::logout();
            return response()->json([
                'status' => 200,
                'message' => 'خروج با موفقیت انجام شد',
            ]) ;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ]) ;
        }
    }

    public function forgetPassword(Request $request){
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
        ]);
        try {
            if($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors(),
                ]);
            }
            $user = User::where('email' , $request->email)->first();
            if ($user) {
                $newPass = Str::random(8);
                $user->password = Hash::make($newPass);
                $user->save();
                $sent = Mail::raw("پسورد جددید شما: $newPass" , function($message)use($request){
                    $message->to($request->email)->subject('تغییر پسورد');
                });
                return response()->json([
                    'status' => 200,
                    'sent' => $sent,
                    'message' => 'پسورد با موفقیت تغییر کرد - به ایمیل خود مراجعه کنید',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'همچین ایمیلی در مجموعه وجود ندارد',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ]) ;
        }
    }

    public function checkLogin(){
        return response(Auth::check());
    }

    public function getAllUsers(){
        return User::orderBy('id' , 'DESC')->get();
    }

    public function delete(Request $request){
        try {
            User::where('id' , $request->id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'کاربر با موفقیت حذف شد',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ]);
        }
    }






}
