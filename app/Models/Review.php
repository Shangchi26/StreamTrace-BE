<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "video_id", "comment"];
    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }
    public function video(){
        return $this->belongsTo(Video::class,"video_id");
    }
}
