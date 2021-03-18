<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public function writer(){
        return $this->belongsTo(User::class)->select('firstname' , 'lastname');
    }

    public function comments(){
        return $this->hasMany(Comment::class)->where('is_active' , 1);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
