<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "package_id", "price"];
    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }
    public function package(){
        return $this->belongsTo(Package::class, "package_id");
    }
}
