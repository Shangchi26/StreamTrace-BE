<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcription extends Model
{
    use HasFactory;

    protected $table = "subcriptions";
    protected $fillable = ["user_id", "provider_id"];
    public function user(){
        return $this->belongTo(User::class, "user_id");
    }
    public function provider(){
        return $this->belongTo(User::class, "provider_id");
    }
}
