<?php

namespace App\Models;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostImage extends Model
{
    protected $fillable = ['post_id','image'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function iamge(){
        return $this->hasOne(PostImage::class);
    }
}
