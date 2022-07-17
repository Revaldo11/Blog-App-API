<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    //like or unlike a post
    public function likeOrUnlike(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }
        $like = $post->likes()->where('users_id', $request->user()->id)->first();
        if ($like) {
            $like->delete();
            return response([
                'message' => 'Like removed successfully'
            ], 200);
        } else {
            $like = $post->likes()->create([
                'users_id' => $request->user()->id,
                'post_id' => $id,
            ]);
            return response([
                'message' => 'Like added successfully'
            ], 200);
        }
    }
}
