<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id", "name", "thumbnail", "src", "view", "description", "status",
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    // Trong mô hình Video
    public function likes()
    {
        return $this->hasMany(FavoriteList::class, 'video_id');
    }
    public function reviews(){
        return $this->hasMany(Review::class,'video_id');
    }
}
