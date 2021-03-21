<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use App\Models\Keyword;
use App\Models\Thing;
use Illuminate\Http\Request;

class OthersControllers extends Controller
{
    public function getThings(){
        $things =  Thing::all();
        return response()->json([
            'status' => 200,
            'data' => $things
        ]);
    }
    public function getKeywords(){
        return  Keyword::all();
    }

    public function getAbilities(){
        return  Ability::where('is_active' , 1)->orderBy('num' , 'ASC')->get();
    }


}
