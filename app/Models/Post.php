<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'body',
        'image',
    ];

    // relasi one to many dengan table users
    // satu user bisa memiliki banyak post
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    // relasi one to many dengan table comments
    // satu post bisa memiliki banyak comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // relasi one to many dengan table likes
    // satu post bisa memiliki banyak likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
